<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventFeatureAdsPackage;
use App\Models\EntertainerFeatureAdsPackage;
use App\Models\VenueFeatureAdsPackage;
use Illuminate\Http\Request;

class FeatureAdsPackagesController extends Controller
{
    public function index(){
        $data['event_ads_packages']=EventFeatureAdsPackage::get();
        $data['talent_ads_packages']=EntertainerFeatureAdsPackage::get();
        $data['venue_ads_packages']=VenueFeatureAdsPackage::get();
        return view('admin.Feature_ads_packages.index',compact('data'));

    }
    public function editEventAdsPackageIndex($event_ads_package_id){
         $data['event_ads_package']=EventFeatureAdsPackage::where('id',$event_ads_package_id)->first();
         return view('admin.Feature_ads_packages.Event.edit',compact('data'));
    }
    public function updateEventAdsPackage(Request $request,$event_ads_package_id){
        $request->validate([
            'title'=>'required',
            'price'=>'required',
            'validity_count'=>'required',
            'validity'=> 'required'
        ]);
        $data=$request->only(['title','price']);
        $data['validity']=$request->validity_count.' '.$request->validity;
        EventFeatureAdsPackage::find($event_ads_package_id)->update($data);
        return redirect()->route('feature_ads_packages.index')->with(['status' => true, 'message' => 'Event Ads Package Updated Successfully']);
    }
    public function editTalentAdsPackageIndex($talent_ads_package_id){
        $data['talent_ads_package']=EntertainerFeatureAdsPackage::where('id',$talent_ads_package_id)->first();
        return view('admin.Feature_ads_packages.Talent.edit',compact('data'));
   }
   public function updateTalentAdsPackage(Request $request,$talent_ads_package_id){
       $request->validate([
           'title'=>'required',
           'price'=>'required',
           'validity_count'=>'required',
           'validity'=> 'required'
       ]);
       $data=$request->only(['title','price']);
       $data['validity']=$request->validity_count.' '.$request->validity;
       EntertainerFeatureAdsPackage::find($talent_ads_package_id)->update($data);
       return redirect()->route('feature_ads_packages.index')->with(['status' => true, 'message' => 'Talent Ads Package Updated Successfully']);
   }
   public function editVenueAdsPackageIndex($venue_ads_package_id){
    $data['venue_ads_package']=VenueFeatureAdsPackage::where('id',$venue_ads_package_id)->first();
    return view('admin.Feature_ads_packages.Venue.edit',compact('data'));
}
public function updateVenueAdsPackage(Request $request,$venue_ads_package_id){
   $request->validate([
       'title'=>'required',
       'price'=>'required',
       'validity_count'=>'required',
       'validity'=> 'required'
   ]);
   $data=$request->only(['title','price']);
   $data['validity']=$request->validity_count.' '.$request->validity;
   VenueFeatureAdsPackage::find($venue_ads_package_id)->update($data);
   return redirect()->route('feature_ads_packages.index')->with(['status' => true, 'message' => 'Venue Ads Package Updated Successfully']);
}
}

