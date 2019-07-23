#  :recycle: microsoft-links-refresh

*Fixes redirects to login pages when a user clicks a link to your site from a Microsoft application*
- Excel
- Word
- PowerPoint

Code based on this [ruby gem](https://github.com/spilliton/fix_microsoft_links)

### Explanations
- [The cause (support.microsoft)](https://support.microsoft.com/en-us/help/899927/you-are-redirected-to-a-logon-page-or-an-error-page-or-you-are-prompte)
- [The actual cause (Stack Overflow)](https://stackoverflow.com/a/2736814/1449624)
- [The workaround for clients (docs.microsoft)](https://docs.microsoft.com/en-us/office/troubleshoot/error-messages/cannot-locate-server-when-click-hyperlink)
- The workaround for server (this package :wink:)

## :package: Install

```shell
$ composer require a1rpun/microsoft-links-refresh
```

## :wrench: Config

**config/services.yaml**
```yml
microsoft_links_refresh:
  class: A1rPun\MicrosoftLinksRefresh
  tags:
    - { name: kernel.event_subscriber }
```

## :muscle: Example usage

**1. Example request object**
```json
{
  "headers": {
    "User-Agent": "Excel"
  }
}
```

**2. Response payload (prettified)**
```html
<html>
  <head>
    <meta http-equiv="refresh" content="0"/>
  </head>
  <body></body>
</html>
```

**3. What happens next?**

The request was discarded initially, now the browser refreshes the page and sends the correct headers like a normal click would do.

### Limitations

:snail: As you can see this service slows down page load but gives the user the experience they deserve!
![Response statistic](./assets/request_example.png)

:exclamation: The `User-Agent` can be spoofed in the browser by your local user-agent-switcher extension or any request application like `curl` or postman.

 :interrobang: Currently uses RegEx to parse the User-Agent, may be vulnarable to a [ReDos](https://www.owasp.org/index.php/Regular_expression_Denial_of_Service_-_ReDoS) by malicious users.

##  :page_with_curl: License

MIT, see LICENSE.
