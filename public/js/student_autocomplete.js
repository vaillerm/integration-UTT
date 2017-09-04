$(document).ready(function() {

    $("#student_autocomplete").on('input', function() {
        const name = $(this).val();
        if (name.length > 2) {
            refreshStudents(name);
        } else {
            $('#student_autocomplete_matches').hide();
        }
    });

    $('#checkinFormSubmit').on('click', function(event) {
        event.preventDefault();
        getSelectedStudentsIds().map(id => $(this).append(`<input name="students[]" type="hidden" value=${id}>`))
        $("#checkinForm").submit();
    });

    function refreshStudents(name) {
        $.ajax({
            url: `/api/student/autocomplete?name=${name}`,
            success: function(students) {
                let html = "";
                students.map(student => {
                    html += `<div class="autocomplete_student" data-id=${student.id}>
                                <span>${student.first_name} ${student.last_name}</span>
                                <span class="autocomplete_select">+</span>
                            </div>`
                });
                $('#student_autocomplete_matches').html(html).show();

                $('.autocomplete_select').on('click', function(e) {
                    const studentName = $(e.target).parent().find('span:first').text();
                    const studentId = $(e.target).parent().attr('data-id');

                    // add only if not already in the list
                    if (!getSelectedStudentsIds().filter(id => id == studentId).length) {
                        $('#student_autocomplete_selected_container').append(`
                            <li class="list-group-item" data-id=${studentId}>
                                ${studentName}
                                <span class="autocomplete_remove">x</span>
                            </li>
                        `);
                    }

                    $(".autocomplete_remove").on('click', function() {
                        $(this).parent().remove();
                    });

                });
            }
        })
    }

    /**
     * Go throught the selected users list, and read the ids
     *
     * @return array
     */
    function getSelectedStudentsIds() {
        let ids = [];
        $('#student_autocomplete_selected_container > li').each(function(i, e) {
            ids.push($(e).attr('data-id'));
        });
        return ids;
    }

});
