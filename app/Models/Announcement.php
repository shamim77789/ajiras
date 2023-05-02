<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	protected $table = 'announcement';
	protected $guarded = [];  
	
	public function announcement_types()
	{
		return $this->hasOne('App\Models\AnnouncementType','id','announcement_type');
	}
	
}
?>