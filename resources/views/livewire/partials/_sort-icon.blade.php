@if ($sortField !== $field)
    <i class="mdi mdi-arrow-up-down"></i>
@elseif ($sortDirection == 'asc')
    <i class="mdi mdi-arrow-up"></i>
@else
    <i class="mdi mdi-arrow-down"></i>
@endif
