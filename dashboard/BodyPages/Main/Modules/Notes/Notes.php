<div class="NotesTab">
    <div class="ViewNotes">
        <table class="ViewNotesdisplay">
            <caption class="ViewNotesTitle">Notes from the team</caption>
            <thead class="ViewNotesdisplayHeader">
                <tr>
                    <th syle="min-width:50%;">
                        Note
                    </th>
                    <th style="max-width: 20%;">
                        From:
                    </th>
                    <th style="max-width: 20%;">
                        Date
                    </th>
                    <th style="max-width: 10%;">
                        Id
                    </th>
                </tr>
            </thead>
            <tbody class="ViewNotesdisplayAddData">
                <tr>
                    <td>Ther are five chairs needed in angela and 2 bottles water</td>
                    <td>Swartz</td>
                    <td>31/01/99</td>
                    <td>099</td>
                </tr>
            </tbody>
            <tfoot>

            </tfoot>
        </table>

    </div>

    <form class="AddNotesForm" action="">
        <label class="AddNotesFormHeading" for="AddNotesFormTextarea">Add your Notes here</label>
        <textarea type="text" name="AddNotesFormText" placeholder="Click here to Start typying" id="AddNotesFormTextarea " class="AddNotesFormText" maxlength="150"  onchange="AddNoteFormBtnCancelShowFunc()"required></textarea>
        <div class="AddNoteFormBtns">
            <button type="button" title="Add Note" id="AddNoteFormBtnA" class="AddNoteFormBtnAccept">
                Add note
            </button>
            <button type="button" title="Cancel Note" id="AddNoteFormBtnC" class="AddNoteFormBtnCancel" >
                Cancel
            </button>
        </div>

    </form>
</div>
<link rel="stylesheet" href="dashboard/BodyPages/Main/Modules/Notes/Styles/Notes.css" media="print" onload="this.media='all'">
<script src="dashboard/BodyPages/Main/Modules/Notes/jsFiles/Notes.js" async="true" defer crossorigin="anonymous"></script>
