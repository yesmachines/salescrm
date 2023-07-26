<!-- Chat Popup -->
<div class="hk-chatbot-popup">
    <header>
        <div class="chatbot-head-top">
            <a class="btn btn-sm btn-icon btn-dark btn-rounded" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-notifications-active"></i><span>Send push notifications</span></a>
                <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-volume-off"></i><span>Mute Chat</span></a>
            </div>
            <span class="text-white">Chat with Us</span>
            <a id="minimize_chatbot" href="javascript:void(0);" class="btn btn-sm btn-icon btn-dark btn-rounded">
                <span class="icon"><span class="feather-icon"><i data-feather="minus"></i></span></span>
            </a>
        </div>
        <div class="separator-full separator-light mt-0 opacity-10"></div>
        <div class="media-wrap">
            <div class="media">
                <div class="media-head">
                    <div class="avatar avatar-sm avatar-soft-primary avatar-icon avatar-rounded position-relative">
                        <span class="initial-wrap">
                            <i class="ri-customer-service-2-line"></i>
                        </span>
                        <span class="badge badge-success badge-indicator badge-indicator-lg badge-indicator-nobdr position-bottom-end-overflow-1"></span>
                    </div>
                </div>
                <div class="media-body">
                    <div class="user-name">Chat Robot</div>
                    <div class="user-status">Online</div>
                </div>
            </div>
        </div>
    </header>
    <div class="chatbot-popup-body">
        <div data-simplebar class="nicescroll-bar">
            <div>
                <div class="init-content-wrap">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <p class="card-text">Hey I am chat robot 😈<br>Do yo have any question regarding our tools?<br><br>Select the topic or start chatting.</p>
                            <button class="btn btn-block btn-primary text-nonecase start-conversation">Start a conversation</button>
                        </div>
                    </div>
                    <div class="btn-wrap">
                        <button class="btn btn-soft-primary text-nonecase btn-rounded start-conversation"><span><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span><span class="btn-text">Just browsing</span></span></button>
                        <button class="btn btn-soft-danger text-nonecase btn-rounded start-conversation"><span><span class="icon"><span class="feather-icon"><i data-feather="credit-card"></i></span></span><span class="btn-text">I have a question regarding pricing</span></span></button>
                        <button class="btn btn-soft-warning text-nonecase btn-rounded start-conversation"><span><span class="icon"><span class="feather-icon"><i data-feather="cpu"></i></span></span><span class="btn-text">Need help for technical query</span></span></button>
                        <button class="btn btn-soft-success text-nonecase btn-rounded start-conversation"><span><span class="icon"><span class="feather-icon"><i data-feather="zap"></i></span></span><span class="btn-text">I have a pre purchase question</span></span></button>
                    </div>
                </div>
                <ul class="list-unstyled d-none">
                    <li class="media sent">
                        <div class="media-body">
                            <div class="msg-box">
                                <div>
                                    <p>I have a plan regarding pricing</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="media received">
                        <div class="avatar avatar-xs avatar-soft-primary avatar-icon avatar-rounded">
                            <span class="initial-wrap">
                                <i class="ri-customer-service-2-line"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <div class="msg-box">
                                <div>
                                    <p>Welcome back!<br>Are you looking to upgrade your existing plan?</p>
                                </div>
                            </div>
                            <div class="msg-box typing-wrap">
                                <div>
                                    <div class="typing">
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                        <div class="dot"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer>
        <div class="chatbot-intro-text fs-7">
            <div class="separator-full separator-light"></div>
            <p class="mb-2">This is jampack's beta version please sign up now to get early access to our full version</p>
            <a class="d-block mb-2" href="#"><u>Give Feedback</u></a>
        </div>
        <div class="input-group d-none">
            <div class="input-group-text overflow-show border-0">
                <button class="btn btn-icon btn-flush-dark flush-soft-hover btn-rounded dropdown-toggle no-caret" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="icon"><span class="feather-icon"><i data-feather="share"></i></span></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-icon avatar-xs avatar-soft-primary avatar-rounded me-3">
                                <span class="initial-wrap">
                                    <i class="ri-image-line"></i>
                                </span>
                            </div>
                            <div>
                                <span class="h6 mb-0">Photo or Video Library</span>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-icon avatar-xs avatar-soft-info avatar-rounded me-3">
                                <span class="initial-wrap">
                                    <i class="ri-file-4-line"></i>
                                </span>
                            </div>
                            <div>
                                <span class="h6 mb-0">Documents</span>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-icon avatar-xs avatar-soft-success avatar-rounded me-3">
                                <span class="initial-wrap">
                                    <i class="ri-map-pin-line"></i>
                                </span>
                            </div>
                            <div>
                                <span class="h6 mb-0">Location</span>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-icon avatar-xs avatar-soft-blue avatar-rounded me-3">
                                <span class="initial-wrap">
                                    <i class="ri-contacts-line"></i>
                                </span>
                            </div>
                            <div>
                                <span class="h6 mb-0">Contact</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <input type="text" id="input_msg_chat_popup" name="send-msg" class="input-msg-send form-control border-0 shadow-none" placeholder="Type something...">
            <div class="input-group-text overflow-show border-0">
                <button class="btn btn-icon btn-flush-dark flush-soft-hover btn-rounded">
                    <span class="icon"><span class="feather-icon"><i data-feather="smile"></i></span></span>
                </button>
            </div>
        </div>
        <div class="footer-copy-text">Powered by <a class="brand-link" href="#"><img src="dist/img/logo-light.png" alt="logo-brand"></a></div>
    </footer>
</div>
<a href="#" class="btn btn-icon btn-floating btn-primary btn-lg btn-rounded btn-popup-open">
    <span class="icon">
        <span class="feather-icon"><i data-feather="message-circle"></i></span>
    </span>
</a>
<div class="chat-popover shadow-xl">
    <p>Try Jampack Chat for free and connect with your customers now!</p>
</div>
<!-- /Chat Popup -->