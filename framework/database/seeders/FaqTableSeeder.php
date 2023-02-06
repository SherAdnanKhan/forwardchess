<?php

use App\Models\Faq\Category;
use App\Models\Faq\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faq_categories')->truncate();
        DB::table('faq_posts')->truncate();

        $categories = [
            'Benefits',
            'Features',
            'Offers',
            'Purchasing',
            'Navigation',
            'Publication',
            'Organizing and Tips',
            'Security',
            'Troubleshooting',
            'Contacting Us',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        $posts = [
            1  => [
                $this->makePost(
                    'What is Forward Chess?',
                    'Forward Chess is an interactive Chess e-book reader for mobile (iOS and Android) and desktop (Windows and Mac). You can purchase your favorite ebooks from leading publishers, play through the moves in the book, try out your own lines and even analyze with the powerful Stockfish engine.'
                ),
                $this->makePost(
                    'What are the advantages of purchasing a book on Forward Chess?',
                    'Forward Chess allows you to take your chess library with you anywhere you go . You can sync your books between your favorite mobile(iOS / Android) and desktop(Windows / Mac) devices . You don’t need a physical chess board . The in - built chess board and engine help you study your favorite books . Other advantages include instant delivery, no shipping cost and free publisher corrections and book updates!
Electronic chess books have other advantages, too: instant delivery, no shipping costs, and even free publisher corrections and updates .'
                ),
                $this->makePost(
                    'How many books are on Forward Chess ?',
                    'There are currently over 300 titles available, with more titles constantly being scheduled for release every month.
                                see also: <a href="https://forwardchess.com/sample/" rel="noopener" target="_blank">Sample</a >'
                ),
            ],
            2  => [
                $this->makePost(
                    'Can I use the app as an analysis board while traveling?',
                    'Yes! You can manually enter moves in the game positions inside the book or use the in-built stockfish engine. However, it is not possible to set up a position from scratch on the chess board.'
                ),
                $this->makePost(
                    'Can I play chess with the app?',
                    'This feature is not directly possible, nor is Forward Chess designed for the purpose of playing a game. However, the workaround to achieve this is to open a board inside the app, switch ON the stockfish engine (Engine toggle icon to the upper right-hand side of the interface) and play your moves against the engine recommended lines.'
                ),
                $this->makePost(
                    'Can I use the app to play a two-person game?',
                    'FC is not primarily designed for this purpose, but a workaround is to create a new board inside the app, then make a move by tapping the board and ask your friend to play for the other side. Now, enjoy the game!'
                ),
                $this->makePost(
                    'Can I use Forward Chess to open my favorite PDF or PGN files?',
                    'It is currently not possible to import files into Forward Chess application. You can use the search box inside the store to locate your favorite book, purchase it and continue your study.'
                ),
                $this->makePost(
                    'If I close a book, will Forward Chess remember where I left off?',
                    'Yes! When you open the book again, Forward Chess will automatically jump to where you left off.'
                ),
                $this->makePost(
                    'Can I bookmark a particular spot?',
                    'This feature is currently possible on Android and iOS only. While reading a chapter, just tap the options icon. 
                    (In Android, the options icon depicts three vertically stacked dots. In iOS, the options icon shows three stacked horizontal lines, each with a small circle superimposed over it.)
                    In both versions, the icon is located in the upper right-hand corner on the far right.
                    Once you have tapped the options icon, tap “Bookmark.” Then create a name for your bookmark and save it.
                    To jump to one of your saved spots, tap the options icon, select “Bookmark,” and then tap the name of the bookmark to which you want to jump.'
                ),
                $this->makePost(
                    'Can I take notes?',
                    'Yes, of course! While viewing a chapter, tap the options icon (the three vertical dots on the far right) and tap “Add Note.”
                            This feature is not possible on the desktop version so far.'
                ),
                $this->makePost(
                    'Can I upload my ChessBase database to Forward Chess?',
                    'Currently, it is not possible to import chess databases (PGN, CBH, etc) or other ebooks (PDF, EPUB, etc) inside the app.'
                ),
                $this->makePost(
                    'Can I sync my purchased books between iOS and Android devices?',
                    'Yes. Purchases made after registering with Forward Cloud can be synced between platforms, but you need to be logged into the Forward Cloud.'
                ),
                $this->makePost(
                    'How can I use Forward Chess on my computer?',
                    'Forward Chess is also available for Windows and Mac desktop/laptop computers. You can download the latest version here
                            <a href="https://storage.googleapis.com/fchess-installers/windows/ForwardChess-1.0.3.exe" rel="noopener" target="_blank">Windows:</a>
                            <a href="https://storage.googleapis.com/fchess-installers/mac/ForwardChess-1.0.3.pkg" rel="noopener" target="_blank">Mac:</a>'
                ),
                $this->makePost(
                    'How can I suggest a feature to add?',
                    'Please share your thoughts with us. We value user feedback highly and are constantly striving to improve Forward Chess.
                        Our email is  info@forwardchess.com'
                ),
                $this->makePost(
                    'Can I sample a book before buying it? Are samples free to download?',
                    'Yes! You can download the free sample of any book on the Forward Chess store. In the "Store" tab, locate your favorite book using the search box. Click/tap the "Sample" icon and the download will begin shortly. The Sample will appear in your books section.'
                ),
            ],
            3  => [
                $this->makePost(
                    'If I buy a book from Forward Chess, can I get a discount on the paper version?	',
                    'Forward Chess does not deal with the paper version of the books, but you can directly contact the publishers to see if they would consider such a discount.'
                ),
                $this->makePost(
                    'Where can I get a discount on books?',
                    'Book deals (slashed price books with a big discount) are published every month through our newsletter and also features on our facebook and twitter pages.'
                ),
                $this->makePost(
                    'Where do I enter a discount code?',
                    'On the webstore, during checkout.'
                ),
            ],
            4  => [
                $this->makePost(
                    'How much does Forward Chess cost?',
                    'The Forward Chess app is absolutely free . Currently, we offer two free sample books: GM Sergey Shipov’s “On Life and Chess” and GM Lluis Comas Fabrego’s “True Lies in Chess . ” We also offer free samples of our other books, most of which range from $1.99 to $19.99 . Book price and currency unit may differ from country to country .'
                ),
                $this->makePost(
                    'How do I pay for a chess book ?',
                    'Register or Log in to your FC cloud . Locate the store tab in your Forward Chess application . Search for your favorite book and click the "Buy" button . In Android and iOS, this should trigger an in - app purchase prompt based on the payment options provided . In the desktop versions, this will open a browser tab leading to Forward Chess Webstore where you can log in and complete the purchase .'
                ),
                $this->makePost(
                    'How do I open a book I have purchased ?',
                    'Go to “Books . ” Then click on the title of the book you wish to open .'
                ),
                $this->makePost(
                    'If I purchase a book on my Android device, can I view it on my iPad ?',
                    'Yes!When you register an FC Cloud account, your books are associated with the login information provided . When the same account is opened on another android or iOS(or desktop: Windows / Mac) application, it becomes possible to sync between devices and make your purchases available .'
                ),
                $this->makePost(
                    'How do I download my purchases from the Cloud ?',
                    'You can find the instructions in pdf format for both iOS and Android formats <a href="https://forwardchess.com/download-instructions.html">here</a>'
                ),
                $this->makePost(
                    'Why can\'t I see (or sync) the books I purchased previously? / Doesn\'t Cloud Sync work on my phone ? / Why can\'t I download my books on another device?',
                    'Syncing Up Instructions to update previous purchases to the cloud:
From iOS:
1) Register in the cloud from iOS
2) In iOS app click "Restore transactions" from "Store" section
3) In iOS app click Cloud, then "Send purchases to Cloud"

From Android:
1) Register in the cloud from Android
2) In Android app click Reload Purchases from cloud menu
3) In Android app click Sync to Cloud from cloud menu

From Desktop:
1) Follow any of the above instruction using android or iOS
2) Click Sync in your mobile application
3) Click Sync in the desktop version'
                ),
                $this->makePost(
                    'Can I sync my purchased books between iOS and Android devices?',
                    'Yes. Purchases made after registering with Forward Cloud can be synced between platforms, but you need to be logged into the Forward Cloud.'
                ),
                $this->makePost(
                    'Where can I get a discount on books?',
                    'Book deals (slashed price books with a big discount) are published every month through our newsletter and also features on our facebook and twitter pages.'
                ),
            ],
            5  => [
                $this->makePost(
                    'How do I open a book I have purchased?',
                    'Go to “Books.” Then click on the title of the book you wish to open'
                ),
                $this->makePost(
                    'How do I view the table of contents?',
                    'From the cover page on the mobile version, simply swipe to the next page to view the table of contents. While reading a chapter,
    you can click the page icon (left of the king icon) at the top of the screen to view the table of contents. In the desktop version, you can find the table of contents inside
    the "Content" box next to the Stockfish tab.'
                ),
                $this->makePost(
                    'How do I skip forward to the next chapter?',
                    'Simply swipe from the right in your mobile application. You can also click the page icon to view the table of contents and
    select the next chapter from there. You can also use the arrow marks in the bottom right-hand corner of the screen to navigate to different chapters. In the desktop version,
    you can choose the chapters from the "Content" box next to the "Stockfish" tab.'
                ),
                $this->makePost(
                    'How do I navigate within a chapter?',
                    'You can swipe from the bottom of the screen to scroll down on the mobile version. To return to the start of the
    chapter, tap twice anywhere in the upper region of the text. To go to the end of a chapter, tap twice in the lower region of the text. On the desktop version, you
    can scroll up and down.'
                ),
                $this->makePost(
                    'While reading a chapter, how do I return to my list of books?',
                    'Tap the arrow icon in the upper left-hand region of the screen on your mobile device. In the desktop version, you
    can click the "Library" menu icon on the top left side of the screen.'
                ),
                $this->makePost(
                    'What are the keyboard shortcuts that I can use to navigate inside the app?',
                    'In the desktop version, you can use the UP/DOWN arrows to navigate inside a chapter. It is similar to the Article
    Up/Article Down keys. The Home/End keys go to the top or bottom of any chapter.'
                ),
                $this->makePost(
                    'How can I view my previously saved notes?',
                    'This feature is currently possible only for Android and iOS. In the Android version, you can find a golden comment
    icon to the top right of the screen inside the book. In iOS, there is a comment icon on the right, next to the place where you inserted the note.'
                ),
                $this->makePost(
                    'How does the Forward Chess search function work?',
                    'Books can be searched by author name or title. Use the search box (with the magnifying glass icon) to enter search
    terms.'
                ),
                $this->makePost(
                    ' Why can\'t I see (or sync) the books I purchased previously? / Doesn\'t Cloud Sync work on my phone? / Why can\'t I download
my books on another device?',
                    '
<p style="text-align: center;">Syncing Up Instructions to update previous purchases to the cloud:</p>
<p style="text-align: center;"><strong>From iOS:</strong>
    1) Register in the cloud from iOS
    2) In iOS app click "Restore transactions" from "Store" section
    3) In iOS app click Cloud, then "Send purchases to Cloud"</p>
<p style="text-align: center;"><strong>From Android:</strong>
    1) Register in the cloud from Android
    2) In Android app click Reload Purchases from cloud menu
    3) In Android app click Sync to Cloud from cloud menu</p>
<p style="text-align: center;"><strong>From Desktop:</strong>
    1) Follow any of the above instruction using android or iOS
    2) Click Sync in your mobile application
    3) Click Sync in the desktop version</p>'
                ),
            ],
            6  => [
                $this->makePost(
                    'Which publishers use Forward Chess?',
                    'Books are available from Mongoose Press, New in Chess, Quality Chess, Chess Informant, Chess Publishing, La Casa del Ajedrez, Chess Stars,
    Russell Enterprises, Metropolitan Chess, Chess Evolution, Russian Chess House, Thinkers Publishing, and various independent authors.'
                ),
                $this->makePost(
                    'Where can I see the list of upcoming new books scheduled to be released on Forward Chess?',
                    'You can view the planned upcoming released in the "Coming Soon" section of the "Books and Periodicals" menu on our website. Or, you can just
        click here &gt; <a href="https://forwardchess.com/books-coming-soon-on-the-forward-chess-app/">Books Coming Soon on Forward Chess</a>'
                ),
                $this->makePost(
                    'I am a chess book author or publisher. How can I make my books available on Forward Chess?',
                    'Please contact us and we will be happy to talk to you. Our email is info@forwardchess.com'
                ),
                $this->makePost(
                    'Can I request that Forward Chess add a particular book?',
                    'We love hearing from our readers. To suggest potential books, you can send us a tweet mentioning the book title and
                @author/@publisher of the book!'
                ),
            ],
            7  => [
                $this->makePost(
                    'How does the Forward Chess search function work?',
                    'Books can be searched by author name or title. Use the search box (with the magnifying glass icon) to enter search terms.'
                ),
                $this->makePost(
                    'How do I delete a book that I no longer need?',
                    'You can delete a book that you no longer need. You don\'t have to worry about losing a purchase. Go to “Books.” Find the book you wish to delete, then long tap it (that is, press and hold over it). The app will give you the option to delete. Tap “Delete” to remove the book, or tap anywhere else to return to your books without deleting it. If you decide later that you do need the book, you can download it again for free from the store. On iOS it is a little different: left swipe the book and you will get a red "Delete" button. This feature is not possible on the desktop version where all the books appear under "Library" and can be categorized using folders.'
                ),
                $this->makePost(
                    'My library is getting too cluttered. Is there anything I can do about it?',
                    'Creating folders and moving your books into them can help organize your cluttered library. Use the red plus (+) button to create folders. It can be found in the toolbar under the search box at the top right corner of FC app. Books can be organized in different folders using the "Move" option by long pressing the individual book icons.'
                ),
                $this->makePost(
                    'Creating folders and moving your books into them can help organize your cluttered library. Use the red plus (+) button to create folders. It can be found in the toolbar under the search box at the top right corner of FC app. Books can be organized in different folders using the "Move" option by long pressing the individual book icons.',
                    'Use the red plus (+) button to create folders. It can be found in the toolbar under the search box at the top right corner of FC app. Books can be organized in different folders using the "Move" option by long pressing the individual book icons. In the desktop version, the books can be dragged and dropped into different folders.'
                ),
                $this->makePost(
                    'How to delete books from a folder?',
                    'Long press the individual book, choose "Delete". Or drag and drop the book back to the main "Library" folder in the desktop version. In iOS, the option is to swipe left and the delete.'
                ),
                $this->makePost(
                    'What are the keyboard shortcuts that I can use to navigate inside the app?',
                    'In the desktop version, you can use UP/DOWN arrow to navigate inside a chapter. It is similar to Article Up/Article Down key. Home/End goes to the top or bottom end of any chapter.'
                ),
                $this->makePost(
                    'Is reading a chess book on a mobile device really a way to improve?',
                    'We believe it is! Because it combines a board, a book, and an engine, Forward Chess will save most readers significant amounts of time – time that can then be spent putting new skills into practice. Also, getting better at chess requires immersion in the game. When you have your chess library available to you wherever you are, you are more likely to spend time on chess that you would otherwise waste.'
                ),
                $this->makePost(
                    'How can I retain more of what I read?',
                    'Try to think for yourself as much as you can. Switch off Stockfish for a while and concentrate on generating your own ideas and analysis. For more on the art of critically reading chess books, you can read the first chapter of GM Fabrego’s “True Lies in Chess,” available for free on Forward Chess. The book comes preinstalled on Android, and it can be downloaded for free on iOS.'
                )
            ],
            8  => [
                $this->makePost(
                    'I lost or broke my phone. Are my chess books gone forever, too?',
                    'Your books are safely stored in the cloud! All you have to do is log in to your new device and download your books. Subsequently, you can also restore all purchases using the following option: On Android and iOS, in the cloud menu click "Restore purchases from the cloud".'
                ),
                $this->makePost(
                    'I bought another e-book on Kindle, Nook, or iBooks. Can I read it on Forward Chess?',
                    'Due to security reasons, it is currently not possible to import any files (books or chess databases) into the app. Books purchases on other devices like Kindle, Nook, etc are not compatible with Forward Chess format.'
                ),
                $this->makePost(
                    'Can I upload my ChessBase database to Forward Chess?',
                    'Currently, it is not possible to import chess databases (PGN, CBH, etc) or other ebooks (PDF, EPUB, etc) inside the app.'
                ),
                $this->makePost(
                    'Does Forward Chess work on jailbroken iPads?',
                    'No. We couldn’t allow Forward Chess to work on jailbroken iOS devices without violating our contractual obligations.'
                )
            ],
            9  => [
                $this->makePost(
                    'What does the error message “Unable to buy item (response 7:Item Already Owned)” mean?',
                    'Please try restarting the device. This will clear the Google cache and (hopefully) fix the error.'
                ),
                $this->makePost(
                    'I have a problem. Could you help me?',
                    'Please e-mail us at <a href="mailto:info@forwardchess.com">info@forwardchess.com</a> and we will do our best to assist you.'
                ),
                $this->makePost(
                    'Forward Chess gives the alert: "Maximum devices reached". What should I do?',
                    'On the Forward Cloud Menu, there\'s a checkbox for enabling and disabling devices. Use it to remove devices from your registered account. In the desktop version, you can use the little computer icon to the top left of the screen. Hovering over the icon will indicate its functionality.'
                ),
                $this->makePost(
                    'I get the following error message: "Device is not activated".',
                    'On the Forward Cloud Menu, make sure the option "Device is enabled" is turned on. In the desktop version, make sure you click the little computer icon (last icon in the top-left row of icons) to enable/disable your device.'
                ),
                $this->makePost(
                    'I forgot my password.',
                    'Tap the cloud icon to open the account options screen. Enter a new password in the \'password\' and \'repeat password\' fields. Tap Save. If the problem persists, write to us at info@forwardchess.com'
                ),
            ],
            10 => [
                $this->makePost(
                    'I am a chess book author or publisher. How can I make my books available on Forward Chess?',
                    'Please contact us and we will be happy to talk to you. Our email is info@forwardchess.com.'
                ),
                $this->makePost(
                    'Can I request that Forward Chess add a particular book?',
                    'We love hearing from our readers. To suggest potential books, you can send us a tweet mentioning the book title and @author/@publisher of the book!'
                ),
                $this->makePost(
                    'How can I suggest a feature to add?',
                    'Please share your thoughts with us. We value user feedback highly and are constantly striving to improve Forward Chess. Our email is info@forwardchess.com'
                )
            ]
        ];

        foreach ($posts as $category => $list) {
            foreach ($list as $counter => $post) {
                Faq::create([
                    'categoryId' => $category,
                    'question'   => $post['question'],
                    'answer'     => $post['answer'],
                    'position'   => $counter
                ]);
            }
        }
    }

    private function makePost($question, $answer)
    {
        return [
            'question' => $question,
            'answer'   => $answer,
        ];
    }
}
