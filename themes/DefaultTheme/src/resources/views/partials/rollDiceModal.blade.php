<div class="modal fade bd-example-modal-lg" id="rollDiceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-flex justify-content-center alert alert-success m-3">
                <h5>
                    سه تا (۵) بیار بلیط قرعه کشی بگیر!
                </h5>
            </div>
            <hr />
            <!-- Modal body -->
            <div class="modal-body">
                <div class="game row ">
                    <div class="container col d-flex justify-content-center">
                        <div id='dice1' class="dice dice-one">
                            <div id="dice-one-side-one" class='side one'>
                                <div class="dot one-1"></div>
                            </div>
                            <div id="dice-one-side-two" class='side two'>
                                <div class="dot two-1"></div>
                                <div class="dot two-2"></div>
                            </div>
                            <div id="dice-one-side-three" class='side three'>
                                <div class="dot three-1"></div>
                                <div class="dot three-2"></div>
                                <div class="dot three-3"></div>
                            </div>
                            <div id="dice-one-side-four" class='side four'>
                                <div class="dot four-1"></div>
                                <div class="dot four-2"></div>
                                <div class="dot four-3"></div>
                                <div class="dot four-4"></div>
                            </div>
                            <div id="dice-one-side-five" class='side five'>
                                <div class="dot five-1"></div>
                                <div class="dot five-2"></div>
                                <div class="dot five-3"></div>
                                <div class="dot five-4"></div>
                                <div class="dot five-5"></div>
                            </div>
                            <div id="dice-one-side-six" class='side six'>
                                <div class="dot six-1"></div>
                                <div class="dot six-2"></div>
                                <div class="dot six-3"></div>
                                <div class="dot six-4"></div>
                                <div class="dot six-5"></div>
                                <div class="dot six-6"></div>
                            </div>
                        </div>
                    </div>
                    <div class="container col d-flex justify-content-center">
                        <div id='dice2' class="dice dice-two">
                            <div id="dice-two-side-one" class='side one'>
                                <div class="dot one-1"></div>
                            </div>
                            <div id="dice-two-side-two" class='side two'>
                                <div class="dot two-1"></div>
                                <div class="dot two-2"></div>
                            </div>
                            <div id="dice-two-side-three" class='side three'>
                                <div class="dot three-1"></div>
                                <div class="dot three-2"></div>
                                <div class="dot three-3"></div>
                            </div>
                            <div id="dice-two-side-four" class='side four'>
                                <div class="dot four-1"></div>
                                <div class="dot four-2"></div>
                                <div class="dot four-3"></div>
                                <div class="dot four-4"></div>
                            </div>
                            <div id="dice-two-side-five" class='side five'>
                                <div class="dot five-1"></div>
                                <div class="dot five-2"></div>
                                <div class="dot five-3"></div>
                                <div class="dot five-4"></div>
                                <div class="dot five-5"></div>
                            </div>
                            <div id="dice-two-side-six" class='side six'>
                                <div class="dot six-1"></div>
                                <div class="dot six-2"></div>
                                <div class="dot six-3"></div>
                                <div class="dot six-4"></div>
                                <div class="dot six-5"></div>
                                <div class="dot six-6"></div>
                            </div>
                        </div>
                    </div>
                    <div class="container col d-flex justify-content-center">
                        <div id='dice3' class="dice dice-three">
                            <div id="dice-three-side-one" class='side one'>
                                <div class="dot one-1"></div>
                            </div>
                            <div id="dice-three-side-two" class='side two'>
                                <div class="dot two-1"></div>
                                <div class="dot two-2"></div>
                            </div>
                            <div id="dice-three-side-three" class='side three'>
                                <div class="dot three-1"></div>
                                <div class="dot three-2"></div>
                                <div class="dot three-3"></div>
                            </div>
                            <div id="dice-three-side-four" class='side four'>
                                <div class="dot four-1"></div>
                                <div class="dot four-2"></div>
                                <div class="dot four-3"></div>
                                <div class="dot four-4"></div>
                            </div>
                            <div id="dice-three-side-five" class='side five'>
                                <div class="dot five-1"></div>
                                <div class="dot five-2"></div>
                                <div class="dot five-3"></div>
                                <div class="dot five-4"></div>
                                <div class="dot five-5"></div>
                            </div>
                            <div id="dice-three-side-six" class='side six'>
                                <div class="dot six-1"></div>
                                <div class="dot six-2"></div>
                                <div class="dot six-3"></div>
                                <div class="dot six-4"></div>
                                <div class="dot six-5"></div>
                                <div class="dot six-6"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1 p-2 d-none rollDice-alert-message">
                    <div class="alert alert-warning">
                        متأسفانه شما برنده نشدید، برای داشتن شانسی دوباره لطفا در باره یکی از محصولات ما نظر بدهید تا
                        شانسی دوباره برای برنده شدن داشته باشید!
                    </div>
                </div>
                <div class='m-2 pt-2 row'>
                    <div class="col d-flex justify-content-center align-items-center pt-2 "></div>
                    <div class="col d-flex justify-content-center">
                        <button id="roll" class="roll-button btn btn-success">تاس
                            بریز!</button>
                        <button id="palay-again" class="roll-button btn btn-primary d-none"
                            data-action="{{ url('products/و-پیدا-است-که') }}">
                            نظر بده بلیط بگیر!
                        </button>
                    </div>
                    <div class="col d-flex justify-content-center align-items-center pt-2 "></div>
                </div>
                <div class="row my-4">
                    <div class="col d-flex justify-content-center "><button
                            class="btn btn-success getCode-button d-none button-pulse">دریافت کد
                            روزانه</button></div>
                    <div class="col d-flex justify-content-center"><button class="btn btn-danger cancel-button"
                            data-dismiss="modal">انصراف!</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">


            </div>

        </div>
    </div>
</div>
