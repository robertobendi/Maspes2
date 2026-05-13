# Components — known-good HTML patterns

When BRIEF.md or BUILD_BRIEF calls for one of these, USE THE PATTERN HERE
verbatim (adapt classes/copy to the brand) instead of inventing your own.
Especially: **do not draw fake versions of interactive widgets** (maps,
calendars, charts, audio players, etc.). Either use the real component or
omit it entirely.

If a section in BRIEF.md needs a component you don't see here, prefer
"omit and replace with simpler content" over "invent a fake".

---

## Map — real, free, no key (OpenStreetMap iframe)

```html
<figure class="map-block">
  <iframe
    src="https://www.openstreetmap.org/export/embed.html?bbox=9.0739%2C45.8067%2C9.0939%2C45.8167&layer=mapnik&marker=45.8117%2C9.0839"
    width="100%"
    height="380"
    style="border:0;"
    loading="lazy"
    title="Map — Maspes Piante e Fiori, Como"
  ></iframe>
  <figcaption class="address-card">
    <strong>Maspes Piante e Fiori</strong><br>
    Via Esempio 12, 22100 Como (CO)<br>
    <a href="https://www.openstreetmap.org/?mlat=45.8117&mlon=9.0839#map=16/45.8117/9.0839" target="_blank" rel="noopener">Open in OpenStreetMap</a>
    &middot;
    <a href="https://maps.apple.com/?ll=45.8117,9.0839&q=Maspes+Piante+e+Fiori" target="_blank" rel="noopener">Apple Maps</a>
  </figcaption>
</figure>
```

How to compute the `bbox` and `marker`: take `lat,lon` of the business
(from ANALYSIS.md hard facts), spread ±0.01 in each direction for the
bbox. If you don't know the coordinates, **use the address card alone**
(below) — better than a fake map.

## Address card — no map, just the info

```html
<aside class="address-card">
  <h3>Visit us</h3>
  <address>
    Maspes Piante e Fiori<br>
    Via Esempio 12, 22100 Como (CO)<br>
    <a href="tel:+390310000000">+39 031 000 0000</a><br>
    <a href="mailto:info@maspesfiori.it">info@maspesfiori.it</a>
  </address>
  <p>
    <a href="https://maps.apple.com/?q=Maspes+Piante+e+Fiori+Como" target="_blank" rel="noopener">Get directions</a>
  </p>
</aside>
```

## Contact form — Formspree placeholder (static-site-compatible)

```html
<!-- Replace REPLACE_ME with your Formspree endpoint: https://formspree.io/f/xxxxxxxx -->
<form action="https://formspree.io/f/REPLACE_ME" method="POST" class="contact-form">
  <label>
    <span>Name</span>
    <input name="name" type="text" required>
  </label>
  <label>
    <span>Email</span>
    <input name="email" type="email" required>
  </label>
  <label>
    <span>Message</span>
    <textarea name="message" rows="5" required></textarea>
  </label>
  <input type="text" name="_hp" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
  <button type="submit">Send</button>
</form>
```

If a Formspree endpoint isn't supplied, swap the whole form for a
`mailto:` link + plain address.

## Image gallery — CSS grid, no JS

```html
<section class="gallery">
  <figure><img src="/uploads/img-01.jpg" alt="Description"></figure>
  <figure><img src="/uploads/img-02.jpg" alt="Description"></figure>
  <figure><img src="/uploads/img-03.jpg" alt="Description"></figure>
</section>
```

```css
.gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 12px;
}
.gallery img {
  width: 100%;
  height: 100%;
  aspect-ratio: 4 / 3;
  object-fit: cover;
}
```

## FAQ — semantic, no JS

```html
<section class="faq">
  <details>
    <summary>Do you deliver outside the city?</summary>
    <p>Yes — within 30km, same-day if ordered before 11am.</p>
  </details>
  <details>
    <summary>Can I order by phone?</summary>
    <p>Always: <a href="tel:+390310000000">+39 031 000 0000</a>.</p>
  </details>
</section>
```

## Mobile menu — vanilla JS, ~20 lines

```html
<header class="site-header">
  <a href="/" class="brand">Maspes</a>
  <button class="menu-toggle" aria-controls="primary-nav" aria-expanded="false">Menu</button>
  <nav id="primary-nav" class="primary-nav">
    <a href="/">Home</a>
    <a href="/composizioni">Composizioni</a>
    <a href="/contatti">Contatti</a>
  </nav>
</header>

<script>
  const t = document.querySelector('.menu-toggle');
  const n = document.querySelector('#primary-nav');
  t && t.addEventListener('click', () => {
    const open = t.getAttribute('aria-expanded') === 'true';
    t.setAttribute('aria-expanded', String(!open));
    n.classList.toggle('open');
  });
</script>
```

Show `.primary-nav` inline at desktop, collapse at mobile via `@media (max-width: 720px)`. Show `.menu-toggle` only at mobile.

## Testimonial / pull quote

```html
<blockquote class="testimonial">
  <p>"They've been our florist for fifteen years. Corrado remembers what we ordered last Easter."</p>
  <cite>— Famiglia Rossi, cliente dal 2010</cite>
</blockquote>
```

Use real reviews from `source/text.md` when present. Don't fabricate.

## Pricing table — semantic

```html
<table class="pricing">
  <caption>Rose composition prices</caption>
  <thead><tr><th>Quantity</th><th>Price</th></tr></thead>
  <tbody>
    <tr><td>6 roses</td><td>€18</td></tr>
    <tr><td>9 roses</td><td>€25</td></tr>
    <tr><td>12 roses</td><td>€32</td></tr>
    <tr><td>24 roses</td><td>€60</td></tr>
  </tbody>
</table>
```

## Hero with full-bleed image + overlay

```html
<section class="hero" style="background-image: url('/uploads/hero.jpg');">
  <div class="hero-overlay">
    <h1>Fiori di Como dal 1954</h1>
    <p>Rose, bouquet, piante e composizioni preparate a mano da Corrado e consegnate a domicilio.</p>
    <a class="cta" href="/composizioni">Vedi le composizioni</a>
  </div>
</section>
```

```css
.hero {
  min-height: 70vh;
  display: flex;
  align-items: flex-end;
  background-size: cover;
  background-position: center;
}
.hero-overlay {
  background: linear-gradient(0deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0) 60%);
  width: 100%;
  padding: 4rem 2rem;
  color: #fff;
}
```

## Social links — footer block

```html
<ul class="social">
  <li><a href="https://instagram.com/handle" target="_blank" rel="noopener">Instagram</a></li>
  <li><a href="https://facebook.com/handle" target="_blank" rel="noopener">Facebook</a></li>
</ul>
```

Only include channels the business actually has. **Don't invent social
profiles.** If you don't see one in source/text.md, omit.

---

## Hard prohibitions

- **No fake maps** drawn as SVG or canvas. Use OSM iframe or address card.
- **No fake charts/graphs** with invented numbers.
- **No fake calendars** showing fake events.
- **No fake audio/video players** with no actual media.
- **No fake testimonials** with made-up names — use what's in source/text.md.
- **No fake "trusted by" logo strips** with made-up logos.
- **No fake newsletter counts** ("Join 10,000 readers" — unless actually true).

When in doubt: remove the section and find a simpler way to occupy that
real estate. Empty space + good typography is always better than fake
content.
