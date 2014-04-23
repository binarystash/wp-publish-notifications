#WP Publish Notifications

This is a simple Wordpress plugin that emails registered users when a post or page is published. The email contains the post's title and its content. 

The plugin was written to demonstrate WP Cron and WP Mail. Emails are sent in batches ( 10 emails/minute ).

It may serve as basis for a full-featured subscribers plugin.

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the following cron job to your server to ensure that emails get sent every minute. Replace `http://www.mywebsite.com` with your site's URL. Contact your hosting provider for assistance.

  ```
  * * * * * /usr/bin/wget http://www.mywebsite.com
  ```

4. Emails are sent to registered users every time a post or page is published.

## Support

Report bugs at https://github.com/binarystash/wp-publish-notifications/issues.
