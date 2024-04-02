<!doctype html>
<html lang="en" dir="ltr">

<head>
    <?php
    include("./Public/Pages/Common/header.php");
    include "./Public/Pages/Common/auth_user.php";

    // db connect 
    // Function to echo the script for toastr
    function echoToastScript($type, $message)
    {
        echo "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function() { toastr['$type']('$message'); });</script>";
    }

    // Check if there's a toast message set in session, display it, then unset
    //print_r($_SESSION);
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        echoToastScript($toast['type'], $toast['message']);
        unset($_SESSION['toast']); // Clear the toast message from session
    }

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    // Display error message if available
    if (isset($_SESSION['login_error'])) {
        echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']); // Clear the error message
    }


    include './App/db/db_connect.php';
    $userid = $_SESSION['userid'];

    $query = "SELECT *, IF(sender_id = $userid, 'sender', 'receiver') AS role FROM chat_history WHERE receiver_id = $userid OR sender_id = $userid";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "User: " . $row["sender_id"] . " - Reciever: " . $row["receiver_id"] . "<br>";
            // print_r($row);
            $dataArray[] = $row;
        }
    } else {
        echo "0 results";
        $dataArray = null;
    }
    ?>

    <link rel="stylesheet" href="./assets/css/chat_styles.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body class="  ">
    <!-- loader Start -->
    <?php
    // include("./Public/Pages/Common/loader.php");

    ?>
    <!-- loader END -->

    <!-- sidebar  -->
    <?php
    include("./Public/Pages/Common/sidebar.php");

    ?>

    <main class="main-content">
        <?php
        include("./Public/Pages/Common/main_content.php");
        ?>




        <div class="content-inner container-fluid pb-0" id="page_layout">



            <section class="chat-section">
                <div class="chat-container">
                    <!-- start: Sidebar -->
                    <aside class="chat-sidebar">
                        <a href="#" class="chat-sidebar-logo">
                            <i class="ri-chat-1-fill"></i>
                        </a>
                        <ul class="chat-sidebar-menu">
                            <li class="active"><a href="#" data-title="Chats"><i class="ri-chat-3-line"></i></a></li>
                            <li><a href="#" data-title="Contacts"><i class="ri-contacts-line"></i></a></li>
                            <li><a href="#" data-title="Documents"><i class="ri-folder-line"></i></a></li>
                            <li><a href="#" data-title="Settings"><i class="ri-settings-line"></i></a></li>
                            <li class="chat-sidebar-profile">
                                <button type="button" class="chat-sidebar-profile-toggle">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
                                </button>
                                <ul class="chat-sidebar-profile-dropdown">
                                    <li><a href="#"><i class="ri-user-line"></i> Profile</a></li>
                                    <li><a href="#"><i class="ri-logout-box-line"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </aside>
                    <!-- end: Sidebar -->
                    <!-- start: Content -->
                    <div class="chat-content">
                        <!-- start: Content side -->
                        <div class="content-sidebar">
                            <div class="content-sidebar-title">Chats </div>
                            <form action="" class="content-sidebar-form">
                                <input type="search" class="content-sidebar-input" placeholder="Search...">
                                <button type="button" class="content-sidebar-submit"><i class="ri-search-line"></i></button>
                            </form>



                            <div class="content-messages">
                                <ul class="content-messages-list">
                                    <li class="content-message-title"><span>Recently</span></li>



                                    <?php
                                    // Check if $dataArray is not null before processing
                                    if (!empty($dataArray)) {
                                        // Function to compare timestamps for sorting
                                        function sortByTimestamp($a, $b)
                                        {
                                            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
                                        }

                                        // Sort the array by timestamp
                                        usort($dataArray, 'sortByTimestamp');

                                        $conversation_id = 1; // Initialize conversation ID

                                        foreach ($dataArray as $data) {
                                            // Extracting data for each record
                                            $chatId = $data['chat_id'];
                                            $senderId = $data['sender_id'];
                                            $receiverId = $data['receiver_id'];
                                            $timestamp = $data['timestamp'];

                                            // To hide id if receiver is the current user
                                            if ($receiverId == $userid) {
                                                $receiverId = $data['sender_id'];
                                            }

                                            // Query to fetch receiver's name from the database
                                            include './App/db/db_connect.php'; // Include database connection

                                            // Query to fetch receiver's name based on receiverId
                                            $query = "SELECT name FROM user WHERE id = '$receiverId'";

                                            $result = $conn->query($query);
                                            $name = "Someone";

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $name = $row['name']; // Update name with fetched name from database
                                                }
                                            }

                                            // You can customize the image source, name, message, unread count, and time based on your data
                                            $imageSrc = "https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60";

                                            // You can replace this with actual messages from your data
                                            $message = "msg";
                                            // You can replace this with actual unread counts from your data
                                            $unreadCount = 1;
                                            // Format timestamp to display time
                                            $time = date("H:i", strtotime($timestamp));

                                            // Generating HTML for each record
                                            echo '<li>
                                        <a href="#" data-conversation="#conversation-' . $chatId . '">









                                         <img class="content-message-image" src="' . $imageSrc . '" alt="">
                                         <span class="content-message-info">
                                         <span class="content-message-name">' . $name . '</span>
                                         <span class="content-message-text">' . $message . '</span>
                                        </span>
                    <span class="content-message-more">';
                                            // <span class="content-message-unread">' . $unreadCount . '</span>
                                            echo  '<span class="content-message-time">' . $time . '</span>
                    </span>
                </a>
            </li>';
                                        }
                                    }
                                    ?>

                                </ul>
                                <ul class="content-messages-list">
                                    <li class="content-message-title"><span>All</span></li>

                                    <?php

                                    $query2 =  mysqli_query($conn, "SELECT * FROM user");
                                    // print_r($query2);
                                    while ($row = mysqli_fetch_array($query2)) {
                                        // print_r($row);

                                        $an_id = $row['id'];
                                        echo ' 
                            <li>
                                <a href="#" data-conversation="#conversation-' . $an_id . '">
                                    <img class="content-message-image" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
                                    <span class="content-message-info">
                                        <span class="content-message-name">' . $row['name'] . '</span>
                                        <span class="content-message-text">Lorem ipsum dolor sit amet consectetur.</span>
                                    </span>
                                    <span class="content-message-more">
                                        <span class="content-message-unread">5</span>
                                        <span class="content-message-time">' . $row['role'] . '</span>
                                    </span>
                                </a>
                            </li>
                            ';
                                    }
                                    ?>


                                </ul>

                            </div>




                        </div>
                        <!-- end: Content side -->


                        <!-- start: Conversation -->
                        <div class="conversation conversation-default active">
                            <i class="ri-chat-3-line"></i>
                            <p>Select chat and view conversation!</p>
                        </div>


                        <?php


                        if (!empty($dataArray)) {
                            foreach ($dataArray as $data) {

                                $chatId = $data['chat_id'];

                                if ($data['sender_id'] == $userid) {
                                    $tobepass = $data['receiver_id'];
                                } else if ($data['receiver_id'] == $userid) {
                                    $tobepass = $data['sender_id'];
                                }



                                echo '

<div class="conversation" id="conversation-' . $chatId . '">
    <div class="conversation-top">
        <button type="button" class="conversation-back"><i class="ri-arrow-left-line"></i></button>
        <div class="conversation-user">
            <img class="conversation-user-image" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
            <div>
                <div class="conversation-user-name">' . $name . '</div>
                <div class="conversation-user-status online">online</div>
            </div>
        </div>
        <div class="conversation-buttons">
            <button type="button"><i class="ri-phone-fill"></i></button>
            <button type="button"><i class="ri-vidicon-line"></i></button>
            <button type="button"><i class="ri-information-line"></i></button>
        </div>
    </div>
    <div class="conversation-main">

        <ul class="conversation-wrapper">
            <div class="coversation-divider"><span>Chat</span></div>';



                                // foreach ($chatRecords as $row) : 
                                $query = mysqli_query($conn, "SELECT * FROM chat_records WHERE chat_id = $chatId");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    // $chatRecords[] = $row;


                                    // print_r($row);
                        ?>


                                    <!-- <div class="ourphp-conversation-container"></div> -->
                                    <li class="conversation-item <?php echo ($row['from_user'] == $_SESSION['userid']) ? '' : 'me'; ?>">
                                        <div class="conversation-item-side">
                                            <img class="conversation-item-image" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
                                        </div>
                                        <div class="conversation-item-content">
                                            <div class="conversation-item-wrapper">
                                                <div class="conversation-item-box">
                                                    <div class="conversation-item-text">
                                                        <p><?php echo $row['chat_msg']; ?></p>
                                                        <div class="conversation-item-time"><?php echo date("H:i", strtotime($row['timestamp'])); ?></div>
                                                    </div>
                                                    <div class="conversation-item-dropdown">
                                                        <button type="button" class="conversation-item-dropdown-toggle"><i class="ri-more-2-line"></i></button>
                                                        <ul class="conversation-item-dropdown-list">
                                                            <li><a href="#"><i class="ri-share-forward-line"></i> Forward</a></li>
                                                            <li><a href="#"><i class="ri-delete-bin-line"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>

                        <?php

                                echo '

                            
                        </ul>
                    </div>



                    <form id="messageForm' . $tobepass . '">
    <div class="conversation-form">
        <button type="button" class="conversation-form-button"><i class="ri-emotion-line"></i></button>
        <div class="conversation-form-group">

            <textarea id="messageInput' . $tobepass . '" class="conversation-form-input" rows="1" placeholder="Type here..."></textarea>
            <input type="hidden" id="receiver_id' . $tobepass . '" name="receiver_id" value="' . $tobepass . '">
            <button type="button" class="conversation-form-record"><i class="ri-mic-line"></i></button>
        </div>
        <button type="submit" class="conversation-form-button conversation-form-submit"><i class="ri-send-plane-2-line"></i></button>
    </div>
</form>
                </div>';
                            }
                        }




                        $query2 =  mysqli_query($conn, "SELECT * FROM user");
                        // print_r($query2);
                        while ($row = mysqli_fetch_array($query2)) {
                            // print_r($row);

                            $an_id = $row['id'];

                            echo '
                <div class="conversation" id="conversation-' . $an_id . '">
                    <div class="conversation-top">
                        <button type="button" class="conversation-back"><i class="ri-arrow-left-line"></i></button>
                        <div class="conversation-user">
                            <img class="conversation-user-image" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
                            <div>
                                <div class="conversation-user-name">' . $row['name'] . '</div>
                                <div class="conversation-user-status online">online</div>
                            </div>
                        </div>
                        <div class="conversation-buttons">
                            <button type="button"><i class="ri-phone-fill"></i></button>
                            <button type="button"><i class="ri-vidicon-line"></i></button>
                            <button type="button"><i class="ri-information-line"></i></button>
                        </div>
                    </div>
                    <div class="conversation-main">

                        <ul class="conversation-wrapper">
                            <div class="coversation-divider"><span>Chat</span></div>




                </ul>
                    </div>



                    <form id="ananonymousForm' . $an_id . '" >
                    <div class="conversation-form">
                        <button type="button" class="conversation-form-button"><i class="ri-emotion-line"></i></button>
                        <div class="conversation-form-group">
                        
                            <textarea id="messageInput2' . $an_id . '" class="conversation-form-input" rows="1" placeholder="Type here..."></textarea>
                            
                            
                            <input type="hidden" id="receiver_id' . $an_id . '" name="receiver_id" value="' . $an_id . '">
            
                            <input type="hidden" name="additionalValue2' . $an_id . '" value="Value 2">
                            
                            <button type="button" class="conversation-form-record"><i class="ri-mic-line"></i></button>
                        </div>
                        <button type="submit" class="conversation-form-button conversation-form-submit"><i class="ri-send-plane-2-line"></i></button>
                    </div>
                </form>
                


                    
                </div>';
                        }

                        ?>





                        <!-- end: Conversation -->
                    </div>
                    <!-- end: Content -->
                </div>
            </section>


        </div>






        <?
        include("./Public/Pages/Common/footer.php");
        // //print_r($_SESSION);
        ?>

    </main>
    <!-- Wrapper End-->
    <!-- Live Customizer start -->
    <!-- Setting offcanvas start here -->
    <?php
    include("./Public/Pages/Common/theme_custom.php");

    ?>

    <!-- Settings sidebar end here -->

    <?php
    include("./Public/Pages/Common/settings_link.php");

    ?>
    <!-- Live Customizer end -->

    <!-- Library Bundle Script -->
    <?php
    include("./Public/Pages/Common/scripts.php");

    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Loop through each form with a dynamic identifier
            $('[id^="ananonymousForm"]').each(function() {
                var form = $(this);
                var an_id = form.attr('id').replace('ananonymousForm', ''); // Extract the dynamic ID
                var messageInput = $('#messageInput2' + an_id);
                var receiverId = $('#receiver_id' + an_id).val();
                console.log(receiverId)
                // Form submission for each form
                form.submit(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    sendMessage2(an_id, messageInput, receiverId);
                });

                // Keypress event for the message input in each form
                messageInput.keypress(function(e) {
                    if (e.which === 13) { // 13 is the key code for Enter
                        e.preventDefault(); // Prevent default form submission
                        sendMessage2(an_id, messageInput, receiverId);
                    }
                });
            });

            // Function to send message for a specific form
            function sendMessage2(an_id, messageInput, receiverId) {
                var message = messageInput.val();

                $.ajax({
                    url: '../App/Logic/update_chat_unknownmsg.php',
                    type: 'POST',
                    data: {
                        message: message,
                        receiver_id: receiverId
                    },
                    success: function(response) {
                        console.log('Message sent successfully for form with ID: ' + an_id);
                        messageInput.val(''); // Clear input field after sending message
                        // Update UI if needed
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending message for form with ID: ' + an_id);
                    }
                });
            }


            $('form[id^="messageForm"]').submit(function(e) {
                e.preventDefault(); // Prevent default form submission
                sendMessage($(this));
            });

            // Keypress event for each form
            $('textarea[id^="messageInput"]').keypress(function(e) {
                if (e.which === 13) { // 13 is the key code for Enter
                    e.preventDefault(); // Prevent default form submission
                    sendMessage($(this).closest('form'));
                }
            });

            // Function to send message for each form
            function sendMessage(form) {
                var message = form.find('textarea').val();
                var receiverId = form.find('input[name="receiver_id"]').val(); // Get the value of the hidden input for receiver ID

                $.ajax({
                    url: '../App/Logic/update_chat_unknownmsg.php',
                    type: 'POST',
                    data: {
                        message: message,
                        receiver_id: receiverId
                    }, // Pass message and receiverId in the POST request
                    success: function(response) {
                        console.log('Message sent successfully');
                        form.find('textarea').val('');
                        // Clear input field after sending message
                        // Update UI if needed
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending message');
                    }
                });
            }
        });

        // start: Sidebar
        document.querySelector('.chat-sidebar-profile-toggle').addEventListener('click', function(e) {
            e.preventDefault()
            this.parentElement.classList.toggle('active')
        })

        document.addEventListener('click', function(e) {
            if (!e.target.matches('.chat-sidebar-profile, .chat-sidebar-profile *')) {
                document.querySelector('.chat-sidebar-profile').classList.remove('active')
            }
        })
        // end: Sidebar



        // start: Coversation
        document.querySelectorAll('.conversation-item-dropdown-toggle').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault()
                if (this.parentElement.classList.contains('active')) {
                    this.parentElement.classList.remove('active')
                } else {
                    document.querySelectorAll('.conversation-item-dropdown').forEach(function(i) {
                        i.classList.remove('active')
                    })
                    this.parentElement.classList.add('active')
                }
            })
        })

        document.addEventListener('click', function(e) {
            if (!e.target.matches('.conversation-item-dropdown, .conversation-item-dropdown *')) {
                document.querySelectorAll('.conversation-item-dropdown').forEach(function(i) {
                    i.classList.remove('active')
                })
            }
        })

        document.querySelectorAll('.conversation-form-input').forEach(function(item) {
            item.addEventListener('input', function() {
                this.rows = this.value.split('\n').length
            })
        })

        document.querySelectorAll('[data-conversation]').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault()
                document.querySelectorAll('.conversation').forEach(function(i) {
                    i.classList.remove('active')
                })
                document.querySelector(this.dataset.conversation).classList.add('active')
            })
        })

        document.querySelectorAll('.conversation-back').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault()
                this.closest('.conversation').classList.remove('active')
                document.querySelector('.conversation-default').classList.add('active')
            })
        })



        function fetchChatRecords(chatId) {
            $.ajax({
                url: '../App/Logic/fetchrecords.php',
                type: 'GET',
                data: {
                    chatId: chatId
                }, // Pass chat ID as a parameter
                success: function(data) {
                    $('.ourphp-conversation-container').html(data); // Update conversation container with fetched data
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching chat records');
                }
            });
        }

        // Fetch chat records for a specific chat ID every 2 seconds
        setInterval(function() {
            fetchChatRecords(chatId);
        }, 2000);
    </script>
</body>

</html>