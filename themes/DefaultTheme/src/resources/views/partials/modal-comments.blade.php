<!-- Modal -->
<div class="modal fade" id="state-comment-modal" tabindex="-1" role="dialog" aria-labelledby="state-comment-modalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="state-comment-modalTitle">نظرات کاربران</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">

                    <form action="{{ route('front.participant.comment') }}" id="state-comment-form" method="post">
                        @csrf
                        <div style="display: flex; align-items: center;" class="my-1">
                            <input type="hidden" name="comment_id" class="comment-id" value="{{ null }}">
                            <input type="hidden" class="state-id" value="" name="state_id">
                            <textarea type="text" name="comment" class="form-control w-100 comment-body"
                                style="flex-grow: 1; margin-right: 10px;"></textarea>

                        </div>

                        <button type="button" class=" btn btn-outline-info state-comment-submit-button"
                            style="margin-right: 10px;"><i class="mdi mdi-send mdi-rotate-180"></i></button>

                    </form>
                </div>
                <div class="state-comment p-0 m-0">

                </div>

            </div>

        </div>
    </div>
</div>
