# IMLINSCRAPER

Webページから画像やリンクの情報をスクレイピングして配列で取得することができるライブラリです。

## Description

**ImlinScraper**は、Webページの画像やリンクの情報を一括で取得することができるライブラリです。一度に複数ページの情報も取得することができます。

## Usage

まず、情報取得のためのインスタンスを生成  
通常は、生成時にページURLを引数で渡します。複数URLを配列で渡すことで一度に複数ページの情報を取得することもできます。  

第一引数：String or Array - ページURL（省略可）  
第二引数：String - UserAgent（省略可）  

```php
use Awesam86\ImlinScraper\Scraper;

$scraper = new Scraper('情報取得したいURL');
```

〜 目的ページの画像情報を取得する方法 〜  
下記は引数なしでメソッドを呼び出していますが  
インスタンス生成時にページURLを引数で渡さなかった場合や、ページURLを変更したい場合は引数でページURLを渡してください。  
また、特定の要素の子要素のみを取得したい場合などは第三引数にXPathの構文を指定することで取得することができます。

第一引数：String or Array - ページURL（省略可）  
第二引数：String - UserAgent（省略可）  
第三引数：String - カスタムXPath（省略可）  

```php
$imgsInfoArray = $scraper->GetImagesData();
//戻り値の配列を出力
var_dump($imgsInfoArray);
```
戻り値の配列のキー  
src => 画像URL  
alt  => 代替テキスト  

〜 目的ページのリンク情報を取得する方法 〜  
第三引数までは、画像情報の取得と同じです。  
第四引数は、外部リンクのみ取得したい場合にtrueにしてください。（デフォルト値はfalse）  

第一引数：String or Array - ページURL（省略可）  
第二引数：String - UserAgent（省略可）  
第三引数：String - カスタムXPath（省略可）  
第四引数：Boolean - 外部リンクのみの抽出（省略可）  

```php
$linksInfoArray = $scraper->GetLinksData();
//戻り値の配列を出力
var_dump($linksInfoArray);
```
戻り値の配列のキー  
href => リンク先URL  
text  => ノード値  

## Install

composerを使って導入。

composer.jsonに以下を記述。

```javascript
{
"require": {
"awesam86/imlinscraper": "~1.0"
}
}

```

composer installで導入。

```
$ composer install
```

あとは使いたい場所でrequire&useを記述するだけです。

```php
<?php
require __DIR__."/vendor/autoload.php";
use Awesam86\ImlinScraper\Scraper;

// code...
```

## LICENCE

This software is released under the MIT License, see LICENSE

