<div class="dir-rtl">
    <small class="text-info">فقط اسم مستعار به کاربران نمایش داده میشود!</small>
    @isset($participant)
        <div class="row">
            <div class="col-12 col-md-12 col-lg-6">
                <div class="m-1">
                    اسم مستعار:
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6">
                <div class="m-1">
                    <input type="text" name="username" value="{{ $participant->username }}" class="form-control nick_name"
                        placeholder="اسم مستعار">
                </div>
            </div>
        </div>
        <input type="hidden" name="participant" value="{{ $participant->id }}">
    @else
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="m-1">
                    اسم مستعار:
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="m-1">
                    <input type="text" name="username" class="form-control" placeholder="اسم مستعار">
                </div>
            </div>
        </div>
    @endisset

</div>
