# Audit Proxy
This project attempts to provide a reverse proxy for the npm registry used by [npm audit](https://docs.npmjs.com/cli/audit) and [yarn audit](https://yarnpkg.com/en/docs/cli/audit) in an attempt to prevent the amount of "503 service unavailable" errors generated the by npm registry. 

# Usage
## With Npm
When running `npm audit` leverage the `--registry` option to point to the reverse proxy like so
```
npm audit --registry http://peterton.nl/ 
```

## With Yarn
Currently Yarn is not supported as `yarn audit` does not allow for the `--registry` option.
After `https://github.com/yarnpkg/yarn/pull/7263` has been merged, yarn will be supported.

# Features
Attempts to keep a cache for audit requests, which partly mitigates ["503 service unavailable" errors](https://github.com/yarnpkg/yarn/issues/6929).
An incoming audit request is proxied to the npm registry and returned, additionally the response is cached for 1 hour.
Consecutive requests in the next hour for the same .lock-file will be returned from cache instead.

# Upcoming features
Keep audit requests fresh as a background job. 

# Development
## Requirements
- Composer
- Php 7.1 or higher
- Mysql database

## Installation
To start working on this project follow these steps:
1. Clone this repository `git clone git@github.com:PtrTn/audit-proxy.git`
2. Install dependencies `composer install`
3. Configure webserver to the `./public` directory

## Deployment
1. Make sure your ssh key is setup on peterton.nl
2. Run `bin/console deploy`