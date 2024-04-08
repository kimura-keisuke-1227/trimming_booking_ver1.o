<!DOCTYPE html>
<html lang="ja">

<head>
    <title>管理画面</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap">
    <link rel="stylesheet" href="{{ asset('/css/admin/tailwind/tailwind.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/css/admin/select2.min.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <script src="{{ asset('/js/main.js')}}"></script>
    <script src="{{ asset('/js/admin/jquery-3.6.0.slim.min.js')}}"></script>
    <script src="{{ asset('/js/admin/select2.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="{{ asset('/css/style.css')}}" >

</head>

<body class="antialiased bg-body text-body font-body">
    <div>
        <div class="container">
            <header>
                <div class="pc_container">
                    <h1>@yield('title')</h1>

                </div>

            </header>

        </div>
        <!-- ▼▼▼▼共通ヘッダー(SP)▼▼▼▼　-->
        <nav class="lg:hidden py-6 px-6 bg-gray-800">
            <div class="flex items-center justify-between">
                <a class="text-2xl text-white font-semibold" href="{{Route('login')}}">トップ画面</a>
                <button class="navbar-burger flex items-center rounded focus:outline-none">
                    <svg class="text-white bg-indigo-500 hover:bg-indigo-600 block h-8 w-8 p-2 rounded" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <title>Mobile menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                    </svg>
                </button>
            </div>
        </nav>
        <!-- ▲▲▲▲共通ヘッダー(SP)▲▲▲▲　-->
        <!-- ▼▼▼▼共通サイドナビ▼▼▼▼　-->

        <div class="hidden lg:block navbar-menu relative z-50">
            <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"></div>
            <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-gray-800 overflow-y-auto">
                <h1 class="px-6 pb-6 mb-6 lg:border-b border-gray-700">
                    <a class="text-xl text-white font-semibold" href="{{ Route('main')}}">トップ画面</a>
                </h1>
                <div class="px-4 pb-6">
                    <h3 class="mb-2 text-xs uppercase text-gray-500 font-medium">メニュー</h3>
                    <ul class="mb-8 text-sm font-medium">
                        @if( Auth::id() == 1 )
                            <li>
                                <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.users.createStaff')}}">
                                    <span class="inline-block mr-3">
                                        <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <span>スタッフ登録</span>
                                </a>
                            </li>

                        @else


                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.checkBookings.dateAndSalon')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.3414 9.23329C11.8689 8.66683 12.166 7.92394 12.1747 7.14996C12.1747 6.31453 11.8428 5.51331 11.2521 4.92257C10.6614 4.33183 9.86015 3.99996 9.02472 3.99996C8.18928 3.99996 7.38807 4.33183 6.79733 4.92257C6.20659 5.51331 5.87472 6.31453 5.87472 7.14996C5.88341 7.92394 6.18057 8.66683 6.70805 9.23329C5.97359 9.59902 5.34157 10.1416 4.86881 10.8122C4.39606 11.4827 4.0974 12.2603 3.99972 13.075C3.9754 13.296 4.03989 13.5176 4.17897 13.6911C4.31806 13.8645 4.52037 13.9756 4.74138 14C4.9624 14.0243 5.18401 13.9598 5.35749 13.8207C5.53096 13.6816 5.64207 13.4793 5.66638 13.2583C5.76583 12.4509 6.15709 11.7078 6.76645 11.1688C7.37582 10.6299 8.16123 10.3324 8.97472 10.3324C9.7882 10.3324 10.5736 10.6299 11.183 11.1688C11.7923 11.7078 12.1836 12.4509 12.283 13.2583C12.3062 13.472 12.4111 13.6684 12.5757 13.8066C12.7403 13.9448 12.9519 14.0141 13.1664 14H13.258C13.4765 13.9748 13.6762 13.8644 13.8135 13.6927C13.9509 13.521 14.0148 13.3019 13.9914 13.0833C13.9009 12.2729 13.6117 11.4975 13.1494 10.8258C12.6871 10.1542 12.066 9.60713 11.3414 9.23329ZM8.99972 8.63329C8.70634 8.63329 8.41955 8.5463 8.17562 8.38331C7.93169 8.22031 7.74156 7.98865 7.62929 7.71761C7.51702 7.44656 7.48765 7.14831 7.54488 6.86058C7.60212 6.57284 7.74339 6.30853 7.95084 6.10108C8.15829 5.89364 8.42259 5.75236 8.71033 5.69513C8.99807 5.63789 9.29632 5.66727 9.56736 5.77954C9.83841 5.89181 10.0701 6.08193 10.2331 6.32586C10.3961 6.5698 10.483 6.85658 10.483 7.14996C10.483 7.54336 10.3268 7.92066 10.0486 8.19883C9.77041 8.47701 9.39312 8.63329 8.99972 8.63329ZM14.833 0.666626H3.16638C2.50334 0.666626 1.86746 0.930018 1.39862 1.39886C0.929774 1.8677 0.666382 2.50358 0.666382 3.16663V14.8333C0.666382 15.4963 0.929774 16.1322 1.39862 16.6011C1.86746 17.0699 2.50334 17.3333 3.16638 17.3333H14.833C15.4961 17.3333 16.132 17.0699 16.6008 16.6011C17.0697 16.1322 17.333 15.4963 17.333 14.8333V3.16663C17.333 2.50358 17.0697 1.8677 16.6008 1.39886C16.132 0.930018 15.4961 0.666626 14.833 0.666626ZM15.6664 14.8333C15.6664 15.0543 15.5786 15.2663 15.4223 15.4225C15.266 15.5788 15.0541 15.6666 14.833 15.6666H3.16638C2.94537 15.6666 2.73341 15.5788 2.57713 15.4225C2.42085 15.2663 2.33305 15.0543 2.33305 14.8333V3.16663C2.33305 2.94561 2.42085 2.73365 2.57713 2.57737C2.73341 2.42109 2.94537 2.33329 3.16638 2.33329H14.833C15.0541 2.33329 15.266 2.42109 15.4223 2.57737C15.5786 2.73365 15.6664 2.94561 15.6664 3.16663V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>予約確認</span>
                            </a>
                        </li>
                        {{-- 
                            <li>
                                <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ route('nonMember.beginBooking')}}">
                                    <span class="inline-block mr-3">
                                        <svg class="text-gray-600 w-5 h-5" viewbox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.09182 8.575C2.01257 8.49913 1.91911 8.43966 1.81682 8.4C1.61394 8.31665 1.38637 8.31665 1.18349 8.4C1.08119 8.43966 0.98774 8.49913 0.908486 8.575C0.832619 8.65425 0.773148 8.74771 0.733486 8.85C0.66967 9.00176 0.652235 9.16902 0.68338 9.33068C0.714525 9.49234 0.792855 9.64115 0.908486 9.75833C0.989487 9.83194 1.0825 9.89113 1.18349 9.93333C1.28324 9.97742 1.39109 10.0002 1.50015 10.0002C1.60921 10.0002 1.71707 9.97742 1.81682 9.93333C1.91781 9.89113 2.01082 9.83194 2.09182 9.75833C2.20745 9.64115 2.28578 9.49234 2.31693 9.33068C2.34807 9.16902 2.33064 9.00176 2.26682 8.85C2.22716 8.74771 2.16769 8.65425 2.09182 8.575ZM4.83349 1.66667H16.5002C16.7212 1.66667 16.9331 1.57887 17.0894 1.42259C17.2457 1.26631 17.3335 1.05435 17.3335 0.833333C17.3335 0.61232 17.2457 0.400358 17.0894 0.244078C16.9331 0.0877975 16.7212 0 16.5002 0H4.83349C4.61247 0 4.40051 0.0877975 4.24423 0.244078C4.08795 0.400358 4.00015 0.61232 4.00015 0.833333C4.00015 1.05435 4.08795 1.26631 4.24423 1.42259C4.40051 1.57887 4.61247 1.66667 4.83349 1.66667ZM2.09182 4.40833C1.97463 4.2927 1.82582 4.21437 1.66416 4.18323C1.50251 4.15208 1.33525 4.16952 1.18349 4.23333C1.0825 4.27554 0.989487 4.33472 0.908486 4.40833C0.832619 4.48759 0.773148 4.58104 0.733486 4.68333C0.689399 4.78308 0.666626 4.89094 0.666626 5C0.666626 5.10906 0.689399 5.21692 0.733486 5.31667C0.775688 5.41765 0.834877 5.51067 0.908486 5.59167C0.989487 5.66528 1.0825 5.72447 1.18349 5.76667C1.28324 5.81075 1.39109 5.83353 1.50015 5.83353C1.60921 5.83353 1.71707 5.81075 1.81682 5.76667C1.91781 5.72447 2.01082 5.66528 2.09182 5.59167C2.16543 5.51067 2.22462 5.41765 2.26682 5.31667C2.31091 5.21692 2.33368 5.10906 2.33368 5C2.33368 4.89094 2.31091 4.78308 2.26682 4.68333C2.22716 4.58104 2.16769 4.48759 2.09182 4.40833ZM16.5002 4.16667H4.83349C4.61247 4.16667 4.40051 4.25446 4.24423 4.41074C4.08795 4.56703 4.00015 4.77899 4.00015 5C4.00015 5.22101 4.08795 5.43298 4.24423 5.58926C4.40051 5.74554 4.61247 5.83333 4.83349 5.83333H16.5002C16.7212 5.83333 16.9331 5.74554 17.0894 5.58926C17.2457 5.43298 17.3335 5.22101 17.3335 5C17.3335 4.77899 17.2457 4.56703 17.0894 4.41074C16.9331 4.25446 16.7212 4.16667 16.5002 4.16667ZM2.09182 0.241667C2.01257 0.165799 1.91911 0.106329 1.81682 0.0666666C1.66506 0.00285041 1.4978 -0.0145849 1.33614 0.0165602C1.17448 0.0477053 1.02567 0.126035 0.908486 0.241667C0.834877 0.322667 0.775688 0.415679 0.733486 0.516667C0.689399 0.616417 0.666626 0.724274 0.666626 0.833333C0.666626 0.942392 0.689399 1.05025 0.733486 1.15C0.775688 1.25099 0.834877 1.344 0.908486 1.425C0.989487 1.49861 1.0825 1.5578 1.18349 1.6C1.33525 1.66382 1.50251 1.68125 1.66416 1.65011C1.82582 1.61896 1.97463 1.54063 2.09182 1.425C2.16543 1.344 2.22462 1.25099 2.26682 1.15C2.31091 1.05025 2.33368 0.942392 2.33368 0.833333C2.33368 0.724274 2.31091 0.616417 2.26682 0.516667C2.22462 0.415679 2.16543 0.322667 2.09182 0.241667ZM16.5002 8.33333H4.83349C4.61247 8.33333 4.40051 8.42113 4.24423 8.57741C4.08795 8.73369 4.00015 8.94565 4.00015 9.16667C4.00015 9.38768 4.08795 9.59964 4.24423 9.75592C4.40051 9.9122 4.61247 10 4.83349 10H16.5002C16.7212 10 16.9331 9.9122 17.0894 9.75592C17.2457 9.59964 17.3335 9.38768 17.3335 9.16667C17.3335 8.94565 17.2457 8.73369 17.0894 8.57741C16.9331 8.42113 16.7212 8.33333 16.5002 8.33333Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <span>予約登録</span>
                                </a>
                            </li>
                            
                            --}}
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.checkOpenClose')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.8802 1.66663H4.2135C3.55068 1.66735 2.91522 1.93097 2.44653 2.39966C1.97785 2.86834 1.71422 3.50381 1.7135 4.16663V15.8333C1.71422 16.4961 1.97785 17.1316 2.44653 17.6003C2.91522 18.0689 3.55068 18.3326 4.2135 18.3333H15.8802C16.543 18.3326 17.1785 18.0689 17.6471 17.6003C18.1158 17.1316 18.3794 16.4961 18.3802 15.8333V4.16663C18.3794 3.50381 18.1158 2.86834 17.6471 2.39966C17.1785 1.93097 16.543 1.66735 15.8802 1.66663ZM4.2135 3.33329H15.8802C16.1011 3.33351 16.3129 3.42138 16.4692 3.57761C16.6254 3.73385 16.7133 3.94568 16.7135 4.16663V10.8333H14.6595C14.385 10.8331 14.1148 10.9007 13.8729 11.0302C13.6309 11.1597 13.4248 11.347 13.2728 11.5755L12.1009 13.3333H7.9928L6.82093 11.5755C6.6689 11.347 6.46273 11.1597 6.22079 11.0302C5.97884 10.9007 5.70863 10.8331 5.43421 10.8333H3.38017V4.16663C3.38039 3.94568 3.46826 3.73385 3.62449 3.57761C3.78072 3.42138 3.99255 3.33351 4.2135 3.33329ZM15.8802 16.6666H4.2135C3.99255 16.6664 3.78072 16.5785 3.62449 16.4223C3.46826 16.2661 3.38039 16.0542 3.38017 15.8333V12.5H5.43421L6.60608 14.2578C6.75811 14.4862 6.96428 14.6736 7.20622 14.803C7.44817 14.9325 7.71838 15.0002 7.9928 15H12.1009C12.3753 15.0002 12.6455 14.9325 12.8875 14.803C13.1294 14.6736 13.3356 14.4862 13.4876 14.2578L14.6595 12.5H16.7135V15.8333C16.7133 16.0542 16.6254 16.2661 16.4692 16.4223C16.3129 16.5785 16.1011 16.6664 15.8802 16.6666Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>空き枠確認</span>
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="<?php echo url('')?>/admin/ownersInfo">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>顧客一覧</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('notification.index')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>お知らせ一覧</span>
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.course.edit')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>コース設定</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.karte.template.index')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>カルテテンプレート編集</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.salon.index')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>店舗別設定</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('accesslog.index')}}">
                                <span class="inline-block mr-3">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>操作ログ確認</span>
                            </a>
                        </li>
                        {{--  
                            <li>
                                <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="{{ Route('admin.setting')}}">
                                    <span class="inline-block mr-3">
                                        <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.6665 6.44996C13.6578 6.3734 13.641 6.29799 13.6165 6.22496V6.14996C13.5764 6.06428 13.5229 5.98551 13.4581 5.91663L8.45813 0.916626C8.38924 0.851806 8.31048 0.79836 8.2248 0.758293H8.1498C8.06514 0.709744 7.97165 0.678579 7.8748 0.666626H2.83313C2.17009 0.666626 1.5342 0.930018 1.06536 1.39886C0.596522 1.8677 0.33313 2.50358 0.33313 3.16663V14.8333C0.33313 15.4963 0.596522 16.1322 1.06536 16.6011C1.5342 17.0699 2.17009 17.3333 2.83313 17.3333H11.1665C11.8295 17.3333 12.4654 17.0699 12.9342 16.6011C13.4031 16.1322 13.6665 15.4963 13.6665 14.8333V6.49996C13.6665 6.49996 13.6665 6.49996 13.6665 6.44996ZM8.66646 3.50829L10.8248 5.66663H9.49979C9.27878 5.66663 9.06682 5.57883 8.91054 5.42255C8.75426 5.26627 8.66646 5.05431 8.66646 4.83329V3.50829ZM11.9998 14.8333C11.9998 15.0543 11.912 15.2663 11.7557 15.4225C11.5994 15.5788 11.3875 15.6666 11.1665 15.6666H2.83313C2.61212 15.6666 2.40015 15.5788 2.24387 15.4225C2.08759 15.2663 1.9998 15.0543 1.9998 14.8333V3.16663C1.9998 2.94561 2.08759 2.73365 2.24387 2.57737C2.40015 2.42109 2.61212 2.33329 2.83313 2.33329H6.9998V4.83329C6.9998 5.49633 7.26319 6.13222 7.73203 6.60106C8.20087 7.0699 8.83675 7.33329 9.49979 7.33329H11.9998V14.8333Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <span>全体設定</span>
                                </a>
                            </li>
                            --}}
                        @endif

                    </ul>
                    <div class="absolute bottom-2 left-4 right-4">
                        <form action="{{route('admin.logout')}}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center pl-3 py-3 pr-2 text-gray-50 hover:bg-gray-900 rounded">
                                <span class="inline-block mr-4">
                                    <svg class="text-gray-600 w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.333618 8.99996C0.333618 9.22097 0.421416 9.43293 0.577696 9.58922C0.733976 9.7455 0.945938 9.83329 1.16695 9.83329H7.49195L5.57528 11.7416C5.49718 11.8191 5.43518 11.9113 5.39287 12.0128C5.35057 12.1144 5.32879 12.2233 5.32879 12.3333C5.32879 12.4433 5.35057 12.5522 5.39287 12.6538C5.43518 12.7553 5.49718 12.8475 5.57528 12.925C5.65275 13.0031 5.74492 13.0651 5.84647 13.1074C5.94802 13.1497 6.05694 13.1715 6.16695 13.1715C6.27696 13.1715 6.38588 13.1497 6.48743 13.1074C6.58898 13.0651 6.68115 13.0031 6.75862 12.925L10.0919 9.59163C10.1678 9.51237 10.2273 9.41892 10.2669 9.31663C10.3503 9.11374 10.3503 8.88618 10.2669 8.68329C10.2273 8.581 10.1678 8.48755 10.0919 8.40829L6.75862 5.07496C6.68092 4.99726 6.58868 4.93563 6.48716 4.89358C6.38564 4.85153 6.27683 4.82988 6.16695 4.82988C6.05707 4.82988 5.94826 4.85153 5.84674 4.89358C5.74522 4.93563 5.65298 4.99726 5.57528 5.07496C5.49759 5.15266 5.43595 5.2449 5.3939 5.34642C5.35185 5.44794 5.33021 5.55674 5.33021 5.66663C5.33021 5.77651 5.35185 5.88532 5.3939 5.98683C5.43595 6.08835 5.49759 6.18059 5.57528 6.25829L7.49195 8.16663H1.16695C0.945938 8.16663 0.733976 8.25442 0.577696 8.4107C0.421416 8.56698 0.333618 8.77895 0.333618 8.99996ZM11.1669 0.666626H2.83362C2.17058 0.666626 1.53469 0.930018 1.06585 1.39886C0.59701 1.8677 0.333618 2.50358 0.333618 3.16663V5.66663C0.333618 5.88764 0.421416 6.0996 0.577696 6.25588C0.733976 6.41216 0.945938 6.49996 1.16695 6.49996C1.38797 6.49996 1.59993 6.41216 1.75621 6.25588C1.91249 6.0996 2.00028 5.88764 2.00028 5.66663V3.16663C2.00028 2.94561 2.08808 2.73365 2.24436 2.57737C2.40064 2.42109 2.6126 2.33329 2.83362 2.33329H11.1669C11.388 2.33329 11.5999 2.42109 11.7562 2.57737C11.9125 2.73365 12.0003 2.94561 12.0003 3.16663V14.8333C12.0003 15.0543 11.9125 15.2663 11.7562 15.4225C11.5999 15.5788 11.388 15.6666 11.1669 15.6666H2.83362C2.6126 15.6666 2.40064 15.5788 2.24436 15.4225C2.08808 15.2663 2.00028 15.0543 2.00028 14.8333V12.3333C2.00028 12.1123 1.91249 11.9003 1.75621 11.744C1.59993 11.5878 1.38797 11.5 1.16695 11.5C0.945938 11.5 0.733976 11.5878 0.577696 11.744C0.421416 11.9003 0.333618 12.1123 0.333618 12.3333V14.8333C0.333618 15.4963 0.59701 16.1322 1.06585 16.6011C1.53469 17.0699 2.17058 17.3333 2.83362 17.3333H11.1669C11.83 17.3333 12.4659 17.0699 12.9347 16.6011C13.4036 16.1322 13.6669 15.4963 13.6669 14.8333V3.16663C13.6669 2.50358 13.4036 1.8677 12.9347 1.39886C12.4659 0.930018 11.83 0.666626 11.1669 0.666626Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>ログアウト</span>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
         
        <div class="mx-auto lg:ml-80">


            <main class="py-4 px-6">
                @if(session()->has('success'))
                <!-- ▼▼▼▼登録完了メッセージ(全ページで共通)▼▼▼▼　-->
                <div class="mb-4 text-right">
                    <div class="pl-6 pr-16 py-4 bg-white border-l-4 border-green-500 shadow-md rounded-r-lg inline-block ml-auto">
                        <div class="flex items-center">
                            <span class="inline-block mr-2">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 0C4.5 0 0 4.5 0 10C0 15.5 4.5 20 10 20C15.5 20 20 15.5 20 10C20 4.5 15.5 0 10 0ZM14.2 8.3L9.4 13.1C9 13.5 8.4 13.5 8 13.1L5.8 10.9C5.4 10.5 5.4 9.9 5.8 9.5C6.2 9.1 6.8 9.1 7.2 9.5L8.7 11L12.8 6.9C13.2 6.5 13.8 6.5 14.2 6.9C14.6 7.3 14.6 7.9 14.2 8.3Z" fill="#17BB84"></path>
                                </svg>
                            </span>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                <!-- ▲▲▲▲登録完了メッセージ▲▲▲▲　-->
                @endif

                <!-- ▼▼▼▼ページ毎の個別内容▼▼▼▼　-->
                <div class="pc_container">
                    @yield('content')

                </div>
                <!-- ▲▲▲▲ページ毎の個別内容▲▲▲▲　-->
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>