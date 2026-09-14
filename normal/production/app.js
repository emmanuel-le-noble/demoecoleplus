function sendSMS() {
    

    alert('bravo');

    const TWILIO_SID = 'YOUR_TWILIO_SID';
    const TWILIO_TOKEN = 'YOUR_TWILIO_TOKEN';
    const url = "https://api.twilio.com/2010-04-01/Accounts/" + TWILIO_SID + "/Messages.json";
    const auth = TWILIO_SID + ":" + TWILIO_TOKEN;

    const myHeader = new Headers({

        'Content-Type':'application/x-www-form-urlencoded',
        'Authorization':'Basic' + btoa(auth)
    });

    const init = ({

        method : 'POST',
        header : myHeader,
        mode : 'cors',
        body : "To=+22893227045&From=%+17728795650&Body=Hellojjjj"

    });

    fetch(url,init)
        .then(response=>console.log(response))
        .catch(error=>console.log(error))

}