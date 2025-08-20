---
title: \logger
generator: Axels php-classdoc; https://github.com/axelhahn/php-classdoc
---

## 📦 Class \logger

```txt

 ----------------------------------------------------------------------
 
 Debug logging during a client request.
 So you can measure any action find bottlenecks in your code.
 
 Licence: GNU GPL 3.0
 Source:  https://github.com/axelhahn/ahlogger
 Docs:    https://www.axel-hahn.de/docs/ahlogger/
 
 USAGE:<br>
 (1) Trigger a message with add() to add a marker<br>
 (2) The render() method lists all items in a table with time since start
     and the delta to the last message. <br>
 
 @author www.axel-hahn.de
 
 ----------------------------------------------------------------------
 2016-02-26  init
 2016-11-19  add memory usage
 (...)
 2022-09-25  add memory tracking, add cli renderer 
 2022-09-27  css updates
 2022-10-02  add emoji chars 
 2022-10-16  mark longest action with an icon 
 2022-12-15  make it compatible to PHP 8.2; add doc + comments
 2023-05-15  fix _getBar() - division by zero
 2024-07-12  php8 only: use variable types; update phpdocs
 2024-09-04  fix short array syntax
 ----------------------------------------------------------------------

```

## 🔶 Properties

(none)

## 🔷 Methods

### 🔹 public __construct()

Constuctor

Line [67](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L67) (7 lines)

**Return**: `void`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sInitMessage | `string` | init message

### 🔹 public add()

Add a logging message

Line [85](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L85) (14 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sMessage | `string` | 
| \<optional\> $sLevel | `string` | 

### 🔹 public enableDebug()

Enable / disable debugging

Line [105](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L105) (4 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bEnable | `bool` | 

### 🔹 public enableDebugByIp()

Enable client debugging by a given array of allowed ip addresses

Line [115](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L115) (11 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aIpArray | `array` | list of ip addresses in a flat array

### 🔹 public render()

Render output of all logging messages

Line [231](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L231) (105 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

### 🔹 public renderCli()

Render output of all logging messages for cli output

Line [341](https://github.com/axelhahn/ahlogger/blob/master/logger.class.php#L341) (20 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
