   <div class="header-outer">
       <div class="header ">
           <a id="mobile_btn" class="d-lg-none mobile_btn float-left" href="#sidebar" style="color: #fff;">
               <i class="fas fa-bars" aria-hidden="true"></i>
           </a>
           <ul class="nav float-left d-lg-none">
               <li>
                   <a href="{{ route('admin.dashboard') }}" class="mobile-logo d-md-block d-block">
                       <img src="{{ asset(setting('primary_logo')) }}" width="80%" alt="" height="50">
                   </a>
               </li>
           </ul>
           <a id="toggle_btn" class="float-left" href="javascript:void(0);">
               <img src="{{ asset('backend/assets/img/navIcon.png') }}" width="47px" alt="">
           </a>
           <ul class="nav float-left">
               <li>
                   <div class="top-nav-search">
                       <a href="javascript:void(0);" class="responsive-search">
                           <i class="fa fa-search"></i>
                       </a>
                       <form>
                           <div class="card align-items-center justify-content-center w-75 mb-0">
                               <div id="clock" class="h3 py-1 mb-0"></div>
                           </div>
                           <script>
                               function updateTime() {
                                   var now = new Date();
                                   var timezoneOffset = 6 * 60 * 60 * 1000;
                                   var localOffset = now.getTimezoneOffset() * 60 * 1000;
                                   var totalOffset = timezoneOffset + localOffset;
                                   var localTime = new Date(now.getTime() + totalOffset);
                                   var hours = localTime.getHours();
                                   var minutes = localTime.getMinutes();
                                   var seconds = localTime.getSeconds();
                                   var meridiem = "AM";
                                   if (hours > 12) {
                                       hours -= 12;
                                       meridiem = "PM";
                                   } else if (hours === 12) {
                                       meridiem = "PM";
                                   } else if (hours === 0) {
                                       hours = 12;
                                   }
                                   if (minutes < 10) {
                                       minutes = "0" + minutes;
                                   }

                                   if (seconds < 10) {
                                       seconds = "0" + seconds;
                                   }

                                   var timeString = hours + ":" + minutes + ":" + seconds + " " + meridiem;
                                   var clock = document.getElementById("clock");
                                   clock.innerHTML = timeString;
                               }
                               setInterval(updateTime, 1000);
                           </script>
                       </form>
                   </div>
               </li>

           </ul>

           <ul class="nav user-menu float-right">
      <!-- note icon -->
<li class="nav-item dropdown d-sm-block" id="noteMenu">
    <a href="javascript:void(0);" class="nav-link" id="noteToggle">
        <img src="{{ asset('backend/assets/img/notes.png') }}" height="30" alt="">
    </a>

    <div class="notification-box" id="noteDropdown" style="display:none; position:absolute; right:200px; top:100%; width:300px; max-height:500px; overflow-y:auto; background:#fff; border:1px solid #fdfdfd; border-radius:5px; z-index:999;">
        <div class="card">
            <div class="card-body p-2">
                @php
                    $officeNotes = App\Models\OfficeNote::latest()->get();
                @endphp
                @forelse ($officeNotes as $item)
                    <div class="note-item mb-2 p-2 border-bottom d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-0 py-0 text-dark">{{ $item->note }}</p>
                            <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}/{{ $item->user->name ?? 'N/A' }}</small>
                        </div>
                        <form action="{{ route('admin.note.destroy', $item->id) }}" method="POST" onsubmit="return confirmDelete(event);" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-transparent border-none" style="border: none;" title="Delete">
                                <i class="bi bi-x-circle text-danger" style="cursor:pointer;font-size:20px"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">No notes found.</p>
                @endforelse
            </div>
        </div>
    </div>
</li>




               <style>
                   #noteDropdown {
                       transition: all 0.2s ease;
                   }

                   #noteDropdown .note-item {
                       cursor: default;
                   }
               </style>


               <li class="nav-item dropdown d-none d-sm-block">
                   <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link header-icon"
                       data-toggle="tooltip" title="Calculator">
                       <img src="{{ asset('backend/assets/img/calculator.png') }}" height="30" alt="">
                   </a>
                   <div class="notification-box border-0 shadow-none">
                       <div class="card">
                           <div class="card-body">
                               <input type="text" class="form-control mb-3" id="result" disabled placeholder="0">
                               <div class="row">

                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('7')">7</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('4')">4</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('1')">1</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="appendValue('*')">*</button>
                                   </div>

                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('8')">8</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('5')">5</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('2')">2</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="appendValue('-')">-</button>
                                   </div>

                               </div>
                               <div class="row">

                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('9')">9</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('6')">6</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('3')">3</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="appendValue('+')">+</button>
                                   </div>

                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="clearValue()">C</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="appendValue('.')">.</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2 number"
                                           onclick="appendValue('0')">0</button>
                                   </div>
                                   <div class="col-3">
                                       <button type="button" class="btn btn-primary btn-block mb-2"
                                           onclick="appendValue('/')">/</button>
                                   </div>

                               </div>
                               <div class="row">
                                   <div class="col-md-12">
                                       <button type="button"
                                           class="btn btn-block btn-secondary h2 py-0 btn-calculate"
                                           onclick="calculate()">=</button>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <script>
                           // Initialize global variables
                           let currentValue = '';
                           let previousValue = '';
                           let operator = '';
                           let operatorClicked = false;

                           // Append value to the input field
                           function appendValue(value) {
                               // check if operator has been clicked
                               if (operatorClicked) {
                                   // replace previous value with current value
                                   previousValue = currentValue;
                                   // reset current value to the new number being clicked
                                   currentValue = value;
                                   // reset operatorClicked flag
                                   operatorClicked = false;
                               } else {
                                   // append value to current value
                                   currentValue += value;
                               }
                               // update input field
                               document.getElementById('result').value = currentValue;
                           }

                           // Clear the input field
                           function clearValue() {
                               currentValue = '';
                               previousValue = '';
                               operator = '';
                               document.getElementById('result').value = '';
                           }

                           // Perform the calculation
                           function calculate() {
                               // Convert values to numbers
                               const currentValueNumber = parseFloat(currentValue);
                               const previousValueNumber = parseFloat(previousValue);

                               // Perform the calculation based on the operator
                               switch (operator) {
                                   case '+':
                                       currentValue = previousValueNumber + currentValueNumber;
                                       break;
                                   case '-':
                                       currentValue = previousValueNumber - currentValueNumber;
                                       break;
                                   case '*':
                                       currentValue = previousValueNumber * currentValueNumber;
                                       break;
                                   case '/':
                                       currentValue = previousValueNumber / currentValueNumber;
                                       break;
                                   default:
                                       break;
                               }

                               // Reset variables
                               previousValue = '';
                               operator = '';

                               // Update input field with result
                               document.getElementById('result').value = currentValue;
                           }

                           // Add event listeners to operator buttons
                           document.querySelectorAll('.btn-primary:not(.number)').forEach(button => {
                               button.addEventListener('click', () => {
                                   // Set the operator and previous value
                                   operator = button.textContent;
                                   previousValue = currentValue;
                                   currentValue = '';
                               });
                           });

                           // Add event listener to equals button
                           document.querySelector('.btn-calculate').addEventListener('click', calculate);
                       </script>
                   </div>
               </li>

               <li class="nav-item dropdown has-arrow">
                   <a href="javascript:;" class=" nav-link user-link" data-toggle="dropdown"
                       style="background: none;">
                       <span class="user-img">
                           <img class="rounded-circle" src="{{ asset('backend/assets/img/user.png') }}"
                               width="40" height="40" alt="Admin">
                           <span class="status online"></span>
                       </span>


                   </a>
                   <div class="dropdown-menu" style="font-size: 16px;">
                       <div class="py-3">
                           <h5 class="text-center text-dark d-block">{{ auth()->user()->name }}</h5>

                           <a class="dropdown-item" href="{{ route('admin.changePassword') }}"><i
                                   class="fas fa-cog"></i>&nbsp; Change
                               password</a>
                           <!-- Dropdown logout -->
                           <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                               class="d-none">
                               @csrf
                           </form>

                           <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                               <i class="fas fa-sign-out-alt"></i>&nbsp; Logout
                           </a>

                       </div>
                   </div>
               </li>
           </ul>


           <div class="dropdown mobile-user-menu float-right">

               <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown"
                   aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
               <div class="dropdown-menu dropdown-menu-right" style="font-size: 16px;">
                   <a class="dropdown-item" href=""><i class="fas fa-user-alt"></i>&nbsp; My Profile</a>
                   <a class="dropdown-item" href=""><i class="fas fa-globe"></i>&nbsp; Site Visit</a>
                   <!-- Dropdown logout -->
                   <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                       @csrf
                   </form>

                   <a class="dropdown-item" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       <i class="fas fa-sign-out-alt"></i>&nbsp; Logout
                   </a>

               </div>
           </div>
       </div>
   </div>


