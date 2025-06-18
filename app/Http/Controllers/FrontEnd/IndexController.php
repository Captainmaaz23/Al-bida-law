<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Logo;
use App\Models\About;
use App\Models\Blogs;
use App\Models\Mission;
use App\Models\OurTeam;
use App\Models\Slidder;
use App\Models\Vission;
use App\Models\ChooseUs;
use App\Models\Question;
use App\Models\Services;
use App\Models\CaseStudy;
use App\Models\ClientReview;
use Illuminate\Http\Request;
use App\Models\FounderMessage;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index(){
        $blogs = Blogs::orderBy('created_at', 'desc')->take(3)->get();
        $about = About::latest()->first();
        $about_images = About::orderby('created_at','desc')->take(3)->get();
        $slidder = Slidder::orderBy('created_by','desc')->take(3)->get();
        $chooseUs = ChooseUs::with('details')->get();
        $casestudy = CaseStudy::first();
        $experienced_attorny = CaseStudy::latest()->take(3)->get();
        $client_review = ClientReview::orderBy('created_at','desc')->take(3)->get();
        $logo = Logo::first();
        // return $client_review;
        return view('front-end.index', compact('blogs','about','about_images','slidder','chooseUs','casestudy','experienced_attorny','client_review','logo'));
    }

    public function blogdetail($id){
            $blogs = Blogs::find($id);
            $recent_blogs = Blogs::orderBy('created_at','desc')->take(3)->get();
            $tags = Blogs::select('tag')->get();
            $services = Services::select('name')->get();
            $logo = Logo::first();
            return view('front-end.blog-details', compact('blogs', 'logo','recent_blogs','tags','services'));        
    }
    
    public function AllBlogs(){
        $logo = Logo::first();
        $blogs = Blogs::orderBy('created_at', 'desc')->paginate(3); // 6 items per page
        return view('front-end.articles',compact('logo','blogs'));
    }

    public function AllServices(){
        $logo = Logo::first();
        $services = Services::orderBy('created_at','desc')->get();
        return view('front-end.services',compact('logo','services'));
    }
    
    public function SingleService($id){
        $logo = Logo::first();
        $single_service = Services::find($id);
        $recent_services = Services::orderBy('created_at','desc')->take(3)->get();
        $services_name = Services::select('name')->get();
        $blog_tag = Blogs::select('tag')->get();
        // return $single_service;
        return view('front-end.service-details',compact('logo','single_service','recent_services','services_name','blog_tag'));
    }

    public function arabicPage(){
        $blogs = Blogs::orderBy('created_at', 'desc')->take(3)->get();
        $about = About::latest()->first();
        $about_images = About::orderby('created_at','desc')->take(3)->get();
        $slidder = Slidder::orderBy('created_by','desc')->take(3)->get();
        $chooseUs = ChooseUs::with('details')->get();
        $casestudy = CaseStudy::first();
        $experienced_attorny = CaseStudy::latest()->take(3)->get();
        $client_review = ClientReview::orderBy('created_at','desc')->take(3)->get();
        $logo = Logo::first();
        // return $client_review;
        return view('front-end.Arabic-Components.index', compact('blogs','about','about_images','slidder','chooseUs','casestudy','experienced_attorny','client_review','logo'));
    }

    public function Single_Arabic_Blog($id){
        $blogs = Blogs::with('user')->find($id);
        $recent_blogs = Blogs::with('user')->orderBy('created_at','desc')->take(3)->get();
        $tags = Blogs::select('arabic_tag')->get();
        $services = Services::select('title_arabic')->get();
        $logo = Logo::first();
        return view('front-end.Arabic-Components.arabic-blog-detail',compact('blogs','recent_blogs','tags','services','logo'));
    }

    public function AllArabicBlogs(){
        $logo = Logo::first();
        $blogs = Blogs::orderBy('created_at', 'desc')->paginate(3); // 6 items per page
        return view('front-end.Arabic-Components.arabic_articles',compact('logo','blogs'));
    }

    public function arabicservice(){
        $logo = Logo::first();
        $services = Services::orderBy('created_at','desc')->get();
        return view('front-end.Arabic-Components.arabic_services',compact('logo','services'));
    }

    public function singlearabicservice($id){
        $logo = Logo::first();
        $single_service = Services::find($id);
        $recent_services = Services::with('user')->orderBy('created_at','desc')->take(3)->get();
        $services_name = Services::select('title_arabic')->get();
        $blog_tag = Blogs::select('arabic_tag')->get();
        // return $blog_tag;
        return view('front-end.Arabic-Components.single_arabicservice',compact('logo','single_service','recent_services','services_name','blog_tag'));
    }

    public function Team(){
        $teams = OurTeam::orderBy('created_by','desc')->paginate(6);
        $logo = Logo::first();
        return view('front-end.team',compact('teams','logo'));
    }

    public function MissionVission(){
        $mission = Mission::latest()->first();
        $vission = Vission::latest()->first();
        $logo = Logo::first();
        return view('front-end.mission-vision',compact('mission','logo','vission'));
    }

    public function FounderMessage()
    {
        $founder = FounderMessage::with('user')
            ->whereHas('user')
            ->latest()
            ->first();
            $logo = Logo::first();
        return view('front-end.foundermessage',compact('founder','logo'));
    }

    public function Faq()
    {
        $faq =  Question::orderBy('created_at','desc')->take(7)->get();
        // return $faq;
        $logo = Logo::first();
        return view('front-end.faq',compact('faq','logo'));
    }
    
}
