@if ($rating)
  <star-rating
    class="rating"
    :star-size="20"
    active-color="#f9b800"
    read-only
    :rating="{{ $rating }}"
    :round-start-rating="false"
    :show-rating="false"
  ></star-rating>
@endif
