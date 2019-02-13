# Aurora Cpanel Change Password plugin for Mail module

Allows users to change passwords on their email accounts hosted by [cPanel](https://cpanel.com/).

How to install a module (taking WebMail Lite as an example of the product built on Aurora framework): [Adding modules in WebMail Lite](https://afterlogic.com/docs/webmail-lite-8/installation/adding-modules)

In `data/settings/modules/MailChangePasswordCpanelPlugin.config.json` file, you need to supply array of mail server names the feature is enabled for. If you put "*" item there, it means the feature is enabled for all accounts.

In the same file, you need to provide access credentials for cPanel user account that controls email accounts.

# License
This module is licensed under AGPLv3 license if free version of the product is used or AfterLogic Software License if commercial version of the product was purchased.
