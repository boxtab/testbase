@foreach($subreferrals as $subreferral)
    <ul>
        <li>{{$subreferral->name}}</li>
        @if(count($subreferral->referrals) && $level < 5)
            @include('profile.child',['subreferrals' => $subreferral->referrals, 'level' => ++$level])
        @endif
    </ul>
@endforeach
