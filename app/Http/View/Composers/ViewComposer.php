<?php 
namespace App\Http\View\Composers;

use App\Models\Color;
use App\Models\FrontSection;
use Illuminate\View\View;

class ViewComposer
{

    //  public function compose(View $view)
    //  {
    //     $view->with('headercolor', Color::where('front_section_id', 1)->get());
        
    //  }

    // public function compose(View $view)
    // {
    //     $front_section_ids = FrontSection::pluck('id');
    
    //     $colors = Color::with('frontsection')
    //         ->where('front_section_id', $front_section_ids)
    //         ->where('color', '!=', '')
    //         ->get();
    //     $background_colors = Color::where('front_section_id', $front_section_ids)
    //         ->where('background_color', '!=', '')
    //         ->get();
    
    //     $view->with('colors', $colors);
    //     $view->with('background_colors', $background_colors);
    // } 


    public function compose(View $view)
    {
        $frontsections = FrontSection::with('color')->get();
        // dd($frontsections->toArray());

        $view->with('FrontSection',$frontsections);
    }
}
