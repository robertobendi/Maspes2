#!/usr/bin/env bash
# scripts/export-static.sh — produce a static snapshot of this PebbleStack site
# under ./docs so GitHub Pages can serve it. Idempotent: rerun any time.

set -euo pipefail

cd "$(dirname "$0")/.."
ROOT="$(pwd)"

PORT="${PORT:-8765}"
HOST="127.0.0.1"
BASE="http://${HOST}:${PORT}"
DOCS="${ROOT}/docs"

SITE_NAME="${SITE_NAME:-$(basename "$ROOT")}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@example.com}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-pebble-$(openssl rand -hex 8)}"
ADMIN_NAME="${ADMIN_NAME:-Admin}"

require() {
  command -v "$1" >/dev/null 2>&1 || { echo "missing required tool: $1"; exit 1; }
}
require php
require curl
require wget

mkdir -p "${ROOT}/data" "${ROOT}/uploads"

php -S "${HOST}:${PORT}" -t "${ROOT}" >/tmp/pebblestack-export.log 2>&1 &
PHP_PID=$!
cleanup() {
  kill "${PHP_PID}" >/dev/null 2>&1 || true
  wait "${PHP_PID}" 2>/dev/null || true
}
trap cleanup EXIT INT TERM

for _ in $(seq 1 40); do
  if curl -sf -o /dev/null "${BASE}/install" || curl -sf -o /dev/null "${BASE}/"; then
    break
  fi
  sleep 0.25
done

curl -sS -o /dev/null -w "install: HTTP %{http_code}\n" \
  -X POST "${BASE}/install" \
  --data-urlencode "email=${ADMIN_EMAIL}" \
  --data-urlencode "password=${ADMIN_PASSWORD}" \
  --data-urlencode "password_confirm=${ADMIN_PASSWORD}" \
  --data-urlencode "name=${ADMIN_NAME}" \
  --data-urlencode "site_name=${SITE_NAME}" || true

# Seed content (idempotent) so the static export captures
# /composizioni, /su-misura, /visitaci with real data.
if [ -f "${ROOT}/scripts/seed-content.php" ]; then
  php "${ROOT}/scripts/seed-content.php" || echo "seed-content.php failed (non-fatal)"
fi

rm -rf "${DOCS}"
mkdir -p "${DOCS}"

echo "Mirroring ${BASE} into ${DOCS} ..."
set +e
wget \
  --recursive --level=8 \
  --convert-links --adjust-extension --page-requisites \
  --no-host-directories \
  --no-verbose \
  --directory-prefix="${DOCS}" \
  --domains="${HOST}" \
  --reject-regex='(/admin|/install|/forms/|/logout|/login)' \
  --tries=2 --timeout=15 \
  "${BASE}/" "${BASE}/sitemap.xml" "${BASE}/robots.txt"
WGET_RC=$?
set -e
if [ "${WGET_RC}" -ne 0 ] && [ "${WGET_RC}" -ne 8 ]; then
  echo "wget failed with exit code ${WGET_RC}"
  exit "${WGET_RC}"
fi

if [ ! -f "${DOCS}/index.html" ]; then
  echo "no index.html exported — check /tmp/pebblestack-export.log"
  exit 1
fi

touch "${DOCS}/.nojekyll"

echo "Static export complete: ${DOCS}"
echo "Files: $(find "${DOCS}" -type f | wc -l | tr -d ' ')"
