$(document).ready(async () => {
    const synth = window.speechSynthesis;

    const inputForm = document.getElementById("formVoice");
    const inputTxt = document.getElementById("inputVoice");
    const voiceSelect = document.getElementById("selectVoice");

    let voices;

    function loadVoices() {
        voices = synth.getVoices();
        for (let i = 0; i < voices.length; i++) {
            const option = document.createElement("option");
            option.textContent = `${voices[i].name} (${voices[i].lang})`;
            option.value = i;
            voiceSelect.appendChild(option);
        }
    }

    // in Google Chrome the voices are not ready on page load
    if ("onvoiceschanged" in synth) {
        synth.onvoiceschanged = loadVoices;
    } else {
        loadVoices();
    }

    inputForm.onsubmit = (event) => {
        event.preventDefault();

        const utterThis = new SpeechSynthesisUtterance(inputTxt.value);
        utterThis.voice = voices[voiceSelect.value];
        synth.speak(utterThis);
        inputTxt.blur();
    };

})