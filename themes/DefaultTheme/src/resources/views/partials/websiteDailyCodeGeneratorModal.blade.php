<div class="modal fade align-items-center" id="websiteDailyCodeGeneratorModal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex justify-content-center m-3"
                style="border-radius: 5px; background-color:rgba(128, 228, 141, 0.5)">
                <h4 class="modal-title">
                    تولید بلیط قرعه کشی
                </h4>
            </div>
            <hr />
            <!-- Modal body -->
            <div class="modal-body m-5 p-5 d-flex align-items-center justify-content-center text-center" style="">
                <div id="output"
                    style="border-radius: 5px;box-shadow: 0px 0px 8px rgba(0, 255, 0); background-color:rgba(0, 0, 0 ,0.5)"
                    class="p-3 m-4 bg-black">
                    <button type="button" class="btn btn-info btn-sm mx-2 d-none codeCopyBtn"
                        action="{{ route('front.lottery.dailyCode') }}">ثبت بلیط!</button>

                    <div class="copy-message">کپی شد!</div> <span class="text-lg text-white m-1"
                        style="color:green;font-size:20px" id="RealNumber"></span><span class="text-lg text-red-700 m-1"
                        style="color:red;font-size:20px" id="RandomNumber"></span>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
