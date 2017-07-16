
Telenor Easy Pay integration. 
==================================================================
#Introduction

Easypay integration script  gives you the ability to integrate with Easypay Online Payment System. Easypay Online Payment System is an electronic payment solution that enables internet users to make financial transaction online. The script can be easily and seamlessly integrated into an existing website, web store or any of your online presence. This script contains the enablement of following two facilities that are provided by Easypay Online Payment System:
1.	Redirection Based Easypay Integration
2.	Support for IPN feature

#Configuration
 Make changes in config.php 
  
call paywitheasypay.php   
 
Following variables are configurable in this panel that needs to be changed according to environment you are using:
I)	StoreId: The Easypay Merchant Store Id that is provided to you by Telenor POC, this will be configured based on the environment you are using.
II)	Order Id Prefix: The prefix to which the orderId will be appended to in order to create a unique orderId.
III)	Merchant’s Token Expiry Days: Number of days the merchant want its OTC token to be expired in, this will be an integer value.
IV)	Auto-Redirect: Either the merchant wants to redirect its customer to it's website at the end of order or not. If yes, then the customer placing the order will be redirected after 10 seconds to the final checkout screen.
V)	Hash-Key: Allows the merchant to send the request to Easypay in an encrypted package, this will prevent any kind of data tempering by any external entity. The merchant needs to copy here the same key which is defined in Easypay Merchant Portal. 
VI)	Payment Method: The method of payment (Credit Card, Mobile Account, Over the Counter or all three) the merchant wants to use for payments.
VII)	Live: Allows the merchant to switch between staging and production environment. Please select No to test Easypay, orders will be routed to staging environment and select Yes for production environment.

After configuring the above mentioned variables Easypay payment method is enabled in your E-Commerce platform.
You are now ready to test the checkout flow of Easypay Payment Method.

Integrating IPN feature
The IPN feature is by default enabled after the enablement of Easypay Payment Method, the merchant just need to configure the URL in the OPS Merchant Portal. The Easypay IPN URL for the merchant is as follows:
