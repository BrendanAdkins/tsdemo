# TSDemo

A plugin that displays a simple donation form on a WordPress site, using Stripe for payment processing.

## Getting Started

Install the plugin on your WordPress site via the admin panel and place the requisite keywords on a page or post.

### Prerequisites

WordPress 4.9 or later, running on a secure site (with a working https address).

### Installing

Use the WP admin dashboard and upload a zipped version of this project. If you're unfamiliar with the process of installing a custom plugin, please see the [WordPress Codex](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) entry for assistance.

### Using

Insert the shortcode \[tsdemo_donation_form\] into a page or post to display the donation form.

## Versioning

This project follows semantic versioning. It is currently in version 1.0.0 and its first stable release will be 2.0.0.

## Authors

* **Brendan Adkins** - https://github.com/BrendanAdkins

## License

This project is licensed under the GPL v2 or later.

## Credits

This project follows the structure of the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate). In addition, it incorporates the [Stripe PHP library](https://github.com/stripe/stripe-php).

## Afterthoughts

I decided to address the exercise via a WordPress plugin, since I have significantly more experience with this CMS framework than with Drupal. I used the aforementioned Boilerplate as a structure for my plugin because it's popular, well-documented and tested, and follows strong best practices in terms of code separation and safety. Given what I know of ThinkShout's embrace of open source projects, I thought it would be acceptable to derive and release this code under the GPL.

Because the exercise recommended a short installation process, I wanted to keep the setup and display of the form simple as well. The client's specific options can be edited through a settings page integrated into the WordPress dashboard, and the donation form can be displayed on any page or post via shortcode, rather than requiring the setup of a specific donation page. The shortcode system turned out not to be integrated into the Boilerplate code by default, but it was pleasantly straightforward to add.

On investigating the options available through Stripe, I decided that their streamlined Checkout popup process would be the most effective way to collect payment data, while avoiding the overhead of writing and validating my own payment form. Changing the donation amount is accomplished simply by updating a namespaced global variable based on a set of radio-button inputs. This means that the Stripe UI is prominent at checkout time, rather than keeping everything on the client's site, but that seemed acceptable for this project.

In retrospect, I should have asked for clarity on part of the exercise, with regard to this sentence: "The client needs to collect who is making the donation, how much it’s for and of course collect the credit card payment." I interpreted that to mean that the client would need internal records of each donation transaction, and spent considerable time and effort setting up a record of donations as private, custom WordPress posts. One particular and inscrutable bug concerning the limitations of custom meta keys took up half a day by itself.

I later took another look at the wording and thought that perhaps an internal record was not necessary; the sentence referred only to the data that would be available through the client's Stripe transaction history. I would have prioritized other work and left the internal records as an extra-credit bonus if I'd understood better at the outset.