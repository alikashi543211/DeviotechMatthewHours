@foreach($sub_cat_list as $sub_cat)
    <option value="{{ $sub_cat->id }}">{{$sub_cat->name}}</option>
@endforeach