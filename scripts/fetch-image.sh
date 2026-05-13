#!/usr/bin/env bash
# scripts/fetch-image.sh QUERY [count] [dest_dir]
#
# Downloads photographs matching QUERY from Wikimedia Commons (no API key).
# Prints saved file paths, one per line. Skips silently on individual
# failures so a missing query doesn't abort the build.
#
# Examples:
#   bash scripts/fetch-image.sh "florist shop interior" 3 uploads/
#   bash scripts/fetch-image.sh "rose bouquet white" 4 uploads/heroes/

set -uo pipefail
QUERY="${1:?usage: fetch-image.sh QUERY [count] [dest_dir]}"
COUNT="${2:-3}"
DEST="${3:-uploads}"
mkdir -p "$DEST"

python3 - "$QUERY" "$COUNT" "$DEST" <<'PY'
import json, os, sys, urllib.parse, urllib.request

QUERY = sys.argv[1]
COUNT = int(sys.argv[2])
DEST  = sys.argv[3]

UA = {"User-Agent": "Bismuth/0.1 image-fetch"}
api = "https://commons.wikimedia.org/w/api.php"

search_q = f"{QUERY} filetype:bitmap"
search = urllib.parse.urlencode({
    "action": "query", "list": "search",
    "srsearch": search_q, "srnamespace": 6,
    "srlimit": str(COUNT * 4), "format": "json",
})

try:
    req = urllib.request.Request(f"{api}?{search}", headers=UA)
    res = json.loads(urllib.request.urlopen(req, timeout=15).read())
except Exception as e:
    print(f"# search failed: {e}", file=sys.stderr)
    sys.exit(0)

hits = res.get("query", {}).get("search", [])
if not hits:
    print(f"# no results for: {QUERY}", file=sys.stderr)
    sys.exit(0)

saved = 0
for hit in hits:
    if saved >= COUNT:
        break
    title = hit["title"]
    info_q = urllib.parse.urlencode({
        "action": "query", "titles": title,
        "prop": "imageinfo", "iiprop": "url|size",
        "iiurlwidth": "1920", "format": "json",
    })
    try:
        ireq = urllib.request.Request(f"{api}?{info_q}", headers=UA)
        info = json.loads(urllib.request.urlopen(ireq, timeout=15).read())
    except Exception:
        continue

    for page in info.get("query", {}).get("pages", {}).values():
        ii = page.get("imageinfo", [])
        if not ii:
            continue
        url = ii[0].get("thumburl") or ii[0].get("url")
        if not url:
            continue
        lower = url.lower().split("?")[0]
        ext = next((e for e in (".jpg", ".jpeg", ".png", ".webp") if lower.endswith(e)), None)
        if not ext:
            continue
        slug = "".join(c.lower() if c.isalnum() else "_" for c in title.replace("File:", "")).strip("_")[:60]
        out = os.path.join(DEST, f"{slug}{ext}")
        try:
            dreq = urllib.request.Request(url, headers=UA)
            with urllib.request.urlopen(dreq, timeout=20) as r:
                data = r.read()
            if len(data) < 4096 or len(data) > 8 * 1024 * 1024:
                continue
            with open(out, "wb") as f:
                f.write(data)
            print(out)
            saved += 1
        except Exception:
            continue
PY
