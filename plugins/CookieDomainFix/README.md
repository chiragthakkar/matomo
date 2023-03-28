    # Matomo CookieDomainFix Plugin

## Description

When the option "Track Visitors across all subdomains" is selected in the tracking code generator it sets the domains and cookie domains to `*.www.[Domain]` when the website URL contains `www`. 
This plugin will set it correctly For example `www.example.com` becomes `*.www.example.com`, plugin will set `*.example.com` instead.


### Run tests for plugin

```
./console tests:run CookieDomainFix
```
