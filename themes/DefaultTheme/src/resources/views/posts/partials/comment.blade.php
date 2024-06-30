<ul class="p-0">
    @foreach ($comments as $item)
        {{-- @dump($comments) --}}

        <div>
            @if ($item->comment_id == null)
                <div class="alert alert-info pr-2 mt-1 mb-0">
                    <strong>{{ $item->user->first_name }}</strong>:
                    <p class="mr-3">{{ $item->body }}</p>
                    <input type="button" class="btn btn-outline-primary btn-sm comment-response-button"
                        data-id="{{ $item->id }}" value="پاسخ">

                </div>
                @auth
                    <div class="reply">
                        {{-- <button type="button" class="btn btn-link">Link</button> --}}
                    </div>
                @endauth
            @endif
            <div class="mb-3">

                @foreach ($item->comments as $key)
                    <div class="mt-1 mb-1 mr-4 alert alert-primary p-0">
                        <strong>{{ $key->user->first_name }}</strong>: <p class="mr-3">{{ $key->body }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    @endforeach
</ul>
