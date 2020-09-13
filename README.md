# twt-sso-php

TwT SSO Library for PHP

## Getting Started

### Add Library to PHP Project

```bash
composer require twt/sso
```

### Initialize SSO Client

```PHP
$sso = new TwT\SSO\Api($app_id, $app_key);
```

Apply for an app ID and app key from your group leader, if you haven't got one already.

### Get Login URL

```PHP
$login_url = $sso->getLoginUrl($redir_url);
```

Returns a URL to a TwT login page, where users can enter their password to login.

After a successful login, the user will be redirected to the `$redir_url` (via an HTTP 302 response). An additional `token={auth_token}` query string will be appended to the URL.

Note that the SSO auth token expires quickly. Instead of reusing it, implement your own authentication instead.

### Get User Info

Now with the auth token, you can fetch user information to identify it.

```PHP
$user_info = $sso->fetchUserInfo($auth_token);
```

User info contains user name, real name, student number, etc. Sensitive information like phone number and ID card number is not included.

## See Also

[twt-sso-node](https://github.com/twtstudio/twt-sso-node)
