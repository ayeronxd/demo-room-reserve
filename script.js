function loadRooms() {
  fetch("get_rooms.php")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById("roomSelect");
      select.innerHTML = '<option value="">Select Room</option>';
      data.forEach(room => {
        const opt = document.createElement("option");
        opt.value = room.id;
        opt.textContent = `Room ${room.name} (Floor ${room.floor})`;
        select.appendChild(opt);
      });
    });
    
    function openForm(roomId) {
            document.getElementById('room_id').value = roomId;
            document.getElementById('reserveModal').style.display = 'block';
        }

        function closeForm() {
            document.getElementById('reserveModal').style.display = 'none';
            document.getElementById('reserveForm').reset();
            document.getElementById('reservationResult').innerText = '';
        }

        document.getElementById('reserveForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('reserve_room.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                document.getElementById('reservationResult').innerText = text;
                if (text.includes('successfully')) {
                    setTimeout(() => {
                        closeForm();
                        location.reload();
                    }, 1500);
                }
            });
        });
        
        function loadRooms() {
  fetch("get_rooms.php")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById("roomSelect");
      select.innerHTML = '<option value="">Select Room</option>';
      data.forEach(room => {
        const opt = document.createElement("option");
        opt.value = room.id;
        opt.textContent = `Room ${room.name} (Floor ${room.floor})`;
        select.appendChild(opt);
      });
    });
}

// Open reservation modal for a specific room
function openForm(roomId) {
  document.getElementById('room_id').value = roomId;
  document.getElementById('reserveModal').style.display = 'block';
}

// Close reservation modal and reset form
function closeForm() {
  document.getElementById('reserveModal').style.display = 'none';
  document.getElementById('reserveForm').reset();
  document.getElementById('reservationResult').innerText = '';
}

// Handle reservation form submission via AJAX
document.getElementById('reserveForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('reserve_room.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.text())
    .then(text => {
      document.getElementById('reservationResult').innerText = text;
      if (text.includes('successfully')) {
        setTimeout(() => {
          closeForm();
          location.reload();
        }, 1500);
      }
    });
});
}