@extends('layouts.app')

@section('content')
<div class="container">
  <div class="gallery">    
    @foreach($items as $item)     
        <div class="card mb-4">
          <a href="{{ route('post.show', ['post_id'=>$item->id]) }}" class="stretched-link"><img class="card-img-top" src="{{ asset('storage/post/' . $item->image) }}" alt=""></a>
        </div>   
    @endforeach
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
  <script>
      const macy = Macy({
          container: '.gallery',
          trueOrder: false,
          waitForImages: false,
          margin: 10,
          columns: 4,
          breakAt: {

              940: 3,
              520: 2,
              400: 1
          }
      });
  </script>
</div>  
@endsection