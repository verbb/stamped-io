# Usage

Stamped.io sends completed Craft Commerce orders to Stamped through Craft's queue. Stamped then handles the review-request workflow. Completing an order and delivering a review request are separate steps, so check both sides when setting up the integration.

## Connect Your Store

Open **Settings → Stamped** in the control panel and enter the public API key, private API key and store hash from the Stamped account for this store. Save the settings. The [Configuration](docs:get-started/configuration) page identifies the corresponding PHP settings if you manage credentials through a configuration file.

Use an order and customer address you control for the first test. Review your Stamped review-request settings before sending it, because the receiving service controls what happens after the order arrives.

## Complete an Order

Complete a Commerce order through your store's normal checkout flow. The plugin listens for order completion and queues a job named **Sending Order to Stamped**. Your Craft queue must be running for that job to deliver the order.

Check the queue after completion. A pending job has not finished transmitting the order. If it fails, inspect the failure and the plugin's log messages, correct the account or request problem, then retry the failed job rather than creating another customer order solely to trigger a send.

## Confirm Delivery

Find the test order in your Stamped account and compare its customer and order information with Commerce. Seeing the queue job complete confirms that the plugin finished its send operation; checking the receiving account confirms that the data is available where you expect it.

If the order is missing, first check whether the queue job exists and has completed. Then verify that the credentials identify the intended store. Review-request timing and email delivery are controlled in Stamped rather than by Craft's queue.
