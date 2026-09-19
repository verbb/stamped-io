Stamped connects completed Craft Commerce orders to Stamped.io’s review workflow. Send the order and customer details Stamped needs so review requests can follow the purchase without manual exports.

When an eligible Craft Commerce order reaches the configured state, queue its customer and line-item information for Stamped.io. The store’s normal order workflow remains the source of truth.

## Features

- **Commerce orders:** Use completed Craft Commerce purchases as the review source.
- **Product details:** Include purchased line items so requests refer to the right products.
- **Customer context:** Send the recipient information required for the follow-up.
- **Queued delivery:** Process the Stamped request through background work.
- **Review workflow:** Hand eligible purchases to Stamped.io for customer review requests.
- **Account configuration:** Keep the Stamped connection and behaviour in project settings.
- **Queued review data:** Background jobs send review data after the order event, keeping Stamped API communication away from the customer’s payment response. Configuration controls the account connection and eligible order behaviour.
