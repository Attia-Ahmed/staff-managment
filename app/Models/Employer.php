<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployerStatus;
use Illuminate\Support\Carbon;

class Employer extends Model
{
    
    use HasFactory;
    protected $fillable=["name","last_status_id"];
    
   protected $hidden=['last_status_id'];

   public function getLastSeenAttribute(){
    $last_status=$this->last_status();
    if($last_status){
        
        $offline_at=$last_status->offline_at;
        
        if($offline_at){
            return $offline_at;
        }
        else{
            return Carbon::now();
        }
    }
    return $this->updated_at;
   }
   
   public function getStatusAttribute(){
     
       $last_status=$this->last_status();
    if($last_status){
        $offline_at=$last_status->offline_at;
        if($offline_at==null){
            return "online";
        }
    
    }
    return "offline";
   }
   /*
   public function last_status()
    {
       // return $this->belongsTo(EmployerStatus::class,'id');
     
        return $this->hasOne(EmployerStatus::class,"employer_id","last_status")->latestOfMany();

    }*/
    public function last_status()
    {
        if($this->last_status_id){
            return EmployerStatus::find($this->last_status_id);
        }
        return null;
       // return $this->hasOne(EmployerStatus::class,"employer_id","last_status_id")->latestOfMany();

    }

}
