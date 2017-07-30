<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    
    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
    //このユーザーがwantしているアイテム　$this＝このユーザー
    public function want_items()
    {
        return $this->items()->where('type', 'want');
    }
    
    //　未wantならwantする
    public function want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば何もしない
            return false;
        } else {
            // 未 Want であれば Want する
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
    }
    
    //　既wantならwantを外す。
     /*detach は type で絞り込んで削除することができないので、直接 SQL をコーディング
      　
      `?`の1つめに配列の1つ目、?の2つめに配列の2つめが入ります。
      つまり、`?`の数と配列の要素数は同じはずです。
      配列＝[\Auth::user()->id, $itemId]　*/

    public function dont_want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば Want を外す
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [\Auth::user()->id, $itemId]);
        } else {
            // 未 Want であれば何もしない
            return false;
        }
    }
    
    //既にwantしているかを判断
     /* is_numeric　とは　変数が数値であるかどうかを調べる
      $item.id と 出力パラメータの itemCode のどちらでも判定しなければいけない
      
      ここにitemId、もしくはitemCodeが入ってくると想定してこの関数（$itemIdOrCode）を作っています。
      
      itemId・・・中間テーブルのidキー（整数）
      itemCode・・・楽天APIコード（見た目は整数だけど文字列）
      idの場合とcodeの場合で、DBからの検索のやり方が変わるので、整数かそうでないかでitemIdなのかitemCodeなのかを切り分けて、それぞれの処理を行っている、ということになります。*/

    public function is_wanting($itemIdOrCode)
    {

      if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
}
