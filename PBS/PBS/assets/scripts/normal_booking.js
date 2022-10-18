// Table Operations
const resultRows = document.querySelectorAll("tr");
const editBtns = document.querySelectorAll(".edit-button");
const deleteBtns = document.querySelectorAll(".delete-button");
const table = document.querySelector("table");
const addRouteForm = document.querySelector("#addRouteForm");


resultRows.forEach(row => 
  row.addEventListener("click", editOrDelete)  
);

if(table)
{
  table.addEventListener("click", collapseForm);
}

function collapseForm(evt){
  if(evt.target.className.includes("btn-close")){
      const collapseRow = evt.target.parentElement.parentElement.parentElement.parentElement;

      // enable the edit button
      const editBtn = collapseRow.previousElementSibling.children[9].children[0];
      editBtn.disabled = false;
      editBtn.classList.remove("disabled");

      // Collapse the row
      collapseRow.remove();
  }
}

function editOrDelete(evt){
  
  if(evt.target.className.includes("edit-button"))
  {
      // Disable the button
      evt.target.disabled = true;
      evt.target.classList.add("disabled");

      const editRow = document.createElement("tr");
      editRow.innerHTML = `
      <td colspan="10">
          <form class="editRouteForm d-flex justify-content-between" action="${evt.target.dataset.link}" method="POST">

              <input type="hidden" name="id" value="${evt.target.dataset.id}">
              <input type="hidden" name="customer_id" value="${evt.target.dataset.customerid}">

              <input type="text" class="form-control" name="cname" value="${evt.target.dataset.name}">
            
              <input type="text" class="form-control cphone" name="cphone" value="${evt.target.dataset.phone}">        
         
              <div class="d-flex justify-content-between">
                  <button type="submit" class="btn btn-success btn-sm" name="edit">SUBMIT</button>
                  <button type="button" class="btn-close align-self-center"></button>
              </div>
          </form>
      </td>
  `;
  
  this.after(editRow);
  }
  // if delete button is clicked
  else if(evt.target.className.includes("delete-button"))
  {
      const deleteInput = document.querySelector("#delete-id");
      const deleteRouteId = document.querySelector("#delete-route-id");
      const deleteBookedSeat = document.querySelector("#delete-booked-seat");
      
      deleteBookedSeat.value = evt.target.dataset.bookedseat;
      deleteRouteId.value = evt.target.dataset.routeid;
      deleteInput.value = evt.target.dataset.id;
  }
}

