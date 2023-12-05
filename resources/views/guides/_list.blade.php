@foreach ($guides as $guide)
   <?php $url = url('tudnivalo',$guide->id)."/".$guide->slug; ?>
   <div class="col-12 col-sm-6 col-md-4 guide">
       <div class="card">
           <div class="card-header"></div>
           <div class="card-title">
               <h3><a href="{{ $url }}">{{ $guide->title }}</a></h3>
           </div>
           <a href="{{ $url }}">
               <div class="image-box">
                   <div class="image" style="background-image:url('/images/posts/{{$guide->image}}');"></div>
               </div>
           </a>
           <div class="card-body">
               <article>
                   <div class="body">{!! $guide->short_description !!}</div>
               </article>
               <a href="{{$url}}"> Részletek</a>
           </div>
       </div>
   </div>
@endforeach

