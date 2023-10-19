@extends('frontent.theme1.layouts.web')
@section('content')
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------  
    START AtAGlance PART
--------------------------------------------------------------------------------------------------------------------------------------------------- -->
<section class="AtAGlance section_gaps">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="AtAGlanceContent">

                    <h1 class="text-center">{{$notice->title ?? $event->title }}</h1>

                    <div class="mt-4 text-center">
                        {!! $notice->content ?? $event->content!!}
                    </div>

                    @if ($notice->pdf)       
                        <div class="text-center mt-3">
                            <a target="_blank" href="{{Config::get('app.s3_url').$notice->pdf}}" class="btn btn-primary"><i class="fa fa-download"></i> Download Pdf</a>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>

</section>
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------  
    START Footer PART
--------------------------------------------------------------------------------------------------------------------------------------------------- -->
@endsection