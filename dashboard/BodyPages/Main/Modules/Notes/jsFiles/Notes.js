function AddNoteFormBtnCancelShowFunc() {
    let AddNotesFormTextarea = document.getElementsByClassName("AddNotesFormText");
    let AddNoteFormBtnCancel = document.getElementById("AddNoteFormBtnC");
    let cancelShow = 0;


    function Checkifinputed() {
        if ((AddNotesFormTextarea[0].value.length > 5) && (cancelShow == 0)) {
            AddNoteFormBtnCancel.classList.add("AddNoteFormBtnCancelShow");
            cancelShow = 1;
        } else if ((AddNotesFormTextarea[0].value.length <= 5) && (cancelShow == 1)) {

            AddNoteFormBtnCancel.classList.remove("AddNoteFormBtnCancelShow");
            cancelShow = 0;
        };
    };
    
    function ClearTextarea(){
        AddNotesFormTextarea[0].value = "";
        AddNoteFormBtnCancel.classList.remove("AddNoteFormBtnCancelShow");
        cancelShow = 0;

    }
    console.log(AddNotesFormTextarea[0].value,cancelShow);
    AddNotesFormTextarea[0].addEventListener("input", Checkifinputed);
    AddNoteFormBtnCancel.addEventListener("click",ClearTextarea);
};
window.addEventListener("load",AddNoteFormBtnCancelShowFunc);
