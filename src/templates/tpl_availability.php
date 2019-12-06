<?php 



function draw_availability_block(){ ?>

    <article id="Avaiabilities">

            <section id="Avaiabilities_Calendars">
                
                <p>Check In</p>
                
                <form action="#s" method="GET">
                    <input id="Avaiabilities_Date_Start" type="date">
                </form>


                <form>
                    <p>Check Out</p>
                    <input id="Avaiabilities_Date_End" type="date">    

                </form>

            </section>

            <section id="Avaiabilities_Number_Guests">

                <p>Select the Number of Guests</p>

                <form action="#s" method="GET">
                    <select name="guests_number">
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                    </select>
                    
                </form>
                
            </section>
            

        </article>

<?php } ?>




