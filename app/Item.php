<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['code', 'name', 'url', 'image_url'];
    
    public function users()
    {
      //withPivotは３つ目の要素を考慮するために必要。（普通、中間テーブルは2つを紐づけるものなので）
      //第3,4引数はルール通りの名前なので不要。
      return $this->belongsToMany(User::class)->withPivot('type')->withTimestamps();
    }
    
    //このアイテムをwantしているユーザー　$this＝このアイテム
    public function want_users()
    {
      return $this->users()->where('type', 'want');
    }
}