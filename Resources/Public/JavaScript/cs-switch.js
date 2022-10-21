/**
 * SupportChat Switch class for backend module
 */
class SupportChatSwitch {

    /**
     * Constructor
     *
     * @constructor
     * @param {array} translations	Translations from typo3 module
     * @param {int} frequencyOfRequest Frequency of request in milliseconds
     *
     * @return void
     */
    constructor(translations, frequencyOfRequest) {
        this.translations = JSON.parse(translations); // translations, wordings
        this.frequency = frequencyOfRequest; // the period for the request
    }

    /**
     * Return current status of switch on/off button with timer function
     */
    getCurrentChatStatus() {

        this.removeTimer(this.timer);

        fetch(TYPO3.settings.ajaxUrls['chat_status'],{
            link: "chain",
            method: 'POST',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then((res) => {
            if (!res.ok) {
                throw new Error(
                    "HTTP exception at response in SupportChatSwitch.getCurrentChatStatus() with status: " + res.status);
            }
            return res.json();
        })
        .then((data) => {
            if (data.length !== 0) {
                this.processCurrentStatus(data);
            }
        });

        // Call request periodically to get changes of front- and backend
        this.getRequestInterval(this.frequency);
    }

    /**
     * Process response of current status
     *
     * @param {json} data response
     */
     processCurrentStatus(data) {
         let elBtn = document.querySelectorAll('.switch-btn');
         let elMsg = document.getElementById('cs-switch-message');
         if (data.state == true) {
            Array.from(elBtn).forEach(function(el) {
                el.classList.remove('switch-off');
                el.classList.add('switch-on');
            });
            elMsg.innerText = this.translations.switch_on_message;
         } else {
             Array.from(elBtn).forEach(function(el) {
                 el.classList.remove('switch-on');
                 el.classList.add('switch-off');
             });
             elMsg.innerText = this.translations.switch_off_message;
         }
    }

    /**
     * Periodic caller of initChat()
     *
     * @param int frequency
     */
     getRequestInterval(frequency) {
        this.timer = setInterval(
            this.getCurrentChatStatus.bind(this),
            frequency || 1000
        );
     }

    /**
     * Remove timer
     *
     * @param timer
     *
     * @returns {null}
     */
    removeTimer(timer) {
        window.clearTimeout(timer);
        window.clearInterval(timer);
        timer = null;
        return null;
    }
}


(function(funcName, baseObj) {
    // The public function name defaults to window.docReady
    // but you can pass in your own object and own function name and those will be used
    // if you want to put them in a different namespace
    funcName = funcName || "docReady";
    baseObj = baseObj || window;
    let readyList = [];
    let readyFired = false;
    let readyEventHandlersInstalled = false;

    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (let i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }

    function readyStateChange() {
        if ( document.readyState === "complete" ) {
            ready();
        }
    }

    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        if (typeof callback !== "function") {
            throw new TypeError("callback for docReady(fn) must be a function");
        }
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete") {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("docReady", window);

docReady(function() {
    // DOM is loaded and ready for manipulation here
    let scSwitch = new SupportChatSwitch(translations, frequencyOfChatRequest);
    scSwitch.getCurrentChatStatus();
});