<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



// class Event extends Model

// {

//     use HasFactory;

//     protected $guarded=[];

//     public function entertainerDetails(){

//         return $this->belongsToMany('App\Models\EntertainerDetail','event_entertainers','event_id','entertainer_details_id')->withPivot('status');

//      }

//      public function eventVenues(){

//         return $this->belongsToMany('App\Models\Venue','event_venues','event_id','venues_id')->withPivot('status');

//      }

//      public function User()

//      {

//          return $this->belongsTo('App\Models\User','user_id','id');

//      }


//      public function reviews()

//      {

//          return $this->hasMany('App\Models\Review','event_id','id');

//      }


class Event extends Model

{
    
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'venue_id',   // ✅ ye zaroor add hona chahiye
        'about_event',
        'event_type',
        'date',
        'end_date',
        'from',
        'to',
        'joining_type',
        'price',
        'seats',
        'description',
        'cover_image',
        'entertainer_id' // Add this line if you want to mass assign entertainer_id
    ];
        public function getCoverImageAttribute($path)
    
        {
        
            if ($path){
        
                return asset($path);
    
            }else{
    
                return null;
    
            }
    
        }
    

    public function eventFeatureAdsPackage()
    
    {
    
        return $this->belongsTo('App\Models\EventFeatureAdsPackage','event_feature_ads_packages_id','id');
    
    }
    
    public function User()
    
    {
        
        return $this->belongsTo('App\Models\User','user_id','id');
        
    }
    // Organizer = User (events.user_id → users.id)
    
    public function organizer()

    {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }



    // Pivot: event_entertainers (event_id → entertainer_details_id)

    // public function entertainers()

    // {

    //     return $this->belongsToMany(EventEntertainers::class, 'event_id', 'id');

    // }

    public function entertainers()

{

    return $this->belongsToMany(

        EntertainerDetail::class,     // Related model

        'event_entertainers',         // Pivot table name

        'event_id',                   // Foreign key on pivot for this model

        'entertainer_details_id'       // Foreign key on pivot for related model

    );

}





    // Pivot: event_venues (event_id → venues_id)

    public function eventVenues()

    {

        return $this->hasMany(EventVenue::class, 'event_id', 'id');

    }



    // Direct venues through pivot

    // public function venues()

    // {

    //     return $this->belongsToMany(Venue::class, 'event_venues', 'event_id', 'venues_id')

    //                 ->withPivot('status');

    // }

   



    // Reviews with users

    public function reviews()

    {

        return $this->hasMany(Review::class, 'event_id', 'id');

    }



    public function tickets()

    {

        return $this->hasMany(EventTicket::class);

    }



    public function venuecategories()

    {

        return $this->belongsTo(VenueCategory::class, 'venue_id', 'id');

    }



    public function entertainerdetails(){

        return $this->belongsTo(EntertainerDetail::class,'entertainer_id','id');

    }



    public function venue()

    {

        return $this->belongsTo(Venue::class, 'venue_id', 'id');

    }

 

}



