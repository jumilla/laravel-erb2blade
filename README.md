
# erb2blace

## 機能

* RailsのテキストテンプレートErbファイル(.*.erb)を、Laravel4のビューテンプレートBladeファイル(.blade.php)に変換します。
* Artisanコマンド`view:erb2blade`で変換できます。

## 対応erbディレクティブ

* `<%# ... %>` => `{{-- --}}`
* `<%= render ... %>` => `@include (...)`
* `<%= ... %>` => `{{ }}`
* `<% if statement %>` => `@if (statement)`
* `<% unless statement %>` => `@if (!(statement))`
* `<% while statement do %>` => `@while (statement)`
* `<% statement.each do |value| %>` => `@foreach (statement as $value)`
* `<% statement.each_with_index do |value, key| %>` => `@foreach (statement as $key => $value)`
* `<% case statement %>` => `<?php switch (statement): ?>`
* `<% when statement %>` => `<?php case statement: ?>`
* `<% else %>` => `@else`
* `<% elsif %>` => `@elseif`
* `<% end %>` => `@end?`
* `<% ... %>` => `<?php ... ?>`

## インストール方法

`composer.json`ファイルを編集します。
行末のカンマはJSON記法に合わせて設定してください。
``` composer.json
	"require": [
		"laravel/framework": "4.*",
		...
		↓追加する
		"jumilla/erb2blade": "1.*"
	],
```

以下のコマンドを実行して、Laravel Extension Packをセットアップしてください。
```
$ composer update

もしくは、

$ php composer.phar update
```

`app/config/app.config`ファイルを編集します。
``` app/config/app.config
	`providers` => [
		`Illuminate\Foundation\Providers\ArtisanServiceProvider`,
		...
		↓追加する
		`Jumilla\Erb2Blade\ServiceProvider`,
	],
```

## コマンド

### php artisan view:erb2blade
`app/views`ディレクトリ下にある全ての`.*.erb`ファイルを、`.blade.php`ファイルに変換します。

## 注意
* <% %>ディレクティブの中の変数まではみていません。手動で変換してください。
* Rubyのcase/when/endブロックの変換は不完全です。`@end`の部分を `<?php endswitch; ?>`に置き換えてください。

## ライセンス
MIT

## 著者
Fumio Furukawa (fumio.furukawa@gmail.com)
