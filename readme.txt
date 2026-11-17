=== Verifiable Portfolio ===
Contributors: yourname
Tags: portfolio, profile, skills, hiring, shortcode
Requires at least: 6.2
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A public portfolio / identity page rendered from a filterable profile — competencies, projects and certifications — via the [verifiable_portfolio] shortcode.

== Description ==

Renders a clean, accessible portfolio page at `/portfolio` (auto-created) from a profile
array: a headline, competency bars with an overall readiness score, projects, and
certifications. It is **decoupled from any record system** — supply real data with the
`verifiable_portfolio_profile` filter, or let it render the built-in demo profile.

No personal data is stored by the plugin; it only renders whatever the filter returns.

== Installation ==

1. Upload the `verifiable-portfolio` folder to `/wp-content/plugins/` and activate.
2. Visit `/portfolio`, or place `[verifiable_portfolio]` on any page.
3. Hook `verifiable_portfolio_profile` to supply your own data.

== Changelog ==

= 1.0.0 =
* Initial public release. Distilled and generalised from a production module.
