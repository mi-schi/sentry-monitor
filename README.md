# sentry-monitor

[![GitHub license](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://raw.githubusercontent.com/mi-schi/sentry-monitor/master/LICENSE)
[![Build Status](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mi-schi/sentry-monitor/?branch=master)
[![Github All Releases](https://img.shields.io/github/downloads/mi-schi/sentry-monitor/total.svg?maxAge=2592000)](https://github.com/mi-schi/sentry-monitor)

## Features

The `sentry-monitor` collect the events (mostly exceptions) from sentry and plot the information in a line chart diagram.

![Screenshot] (screenshot.png)

## Installation

Download the `monitor.phar` file.

     $ curl -OsL https://github.com/mi-schi/sentry-monitor/releases/download/1.1.0/monitor.phar
     $ chmod +x monitor.phar
     
## Usage

### Import the sentry events

Import the sentry events with the following command:

    monitor.phar import [organisation-slug] --sentry-url=https://sentry.domain.com --sentry-api-key=ewd7wg68e76gziefb9eb

If you want to import only one project:

    monitor.phar import [organisation-slug] [project-slug] --sentry-url=https://sentry.domain.com --sentry-api-key=ewd7wg68e76gziefb9eb

If you want to import all projects instead of one or more specific projects use the blacklist:

    monitor.phar import [organisation-slug] --sentry-url=https://sentry.domain.com --sentry-api-key=ewd7wg68e76gziefb9eb --project-blacklist=sentry --project-blacklist=my-test-project

### Show the diagram

Start the build-in server with the default address `http://localhost:8006`:

    monitor.phar run
    
Go to `http://localhost:8006/[organisation-slug]/3/hour`. The three defines the days on the x-axis that will be displayed. You can define `hour` or `day` as x-axis scale.
