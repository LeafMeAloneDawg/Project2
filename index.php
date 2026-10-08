<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mason Curlis">
    <meta name="keywords" content="job application, employment, careers, volunteering">
    <meta name="description" content="index page for Melbourne Youth Support Network job application webpage">
    <link rel="stylesheet" href="./styles/styles.css">
    <title>Melbourne Youth Support Network</title>
    <!-- Embedded CSS for company slogan qoute-->
    <style>
        q {color:#09427e;}
    </style>
</head>

<body class="index-page">

<nav>
    <?php include ("nav.inc"); ?>
</nav>
     <!-- Company logo / banner with text on top -->
        <div id="index_banner">
                <img src="styles/images/grp_3.png" id="company_logo" alt="Company Logo">
                <h1 id="index_logo_text_overlay">Melbourne Youth <br> Support Network</h1>
        </div>
    <main id="index-content">
        <!--    Container for company description and visual content -->
        <div id="index-description" class="content-background-and-border">
            <q class="company-slogan"><b>No young person has to face life alone</b></q>
            <div id="description-image-text">
                <div>

                    <p>Melbourne Youth Support aims to improve young lives through youth
                        outreach, volunteer organisation and fundraising. We firmly believe
                        in connecting with vulnerable youths and
                        providing them personalised support. </p>

                    <p>Early intervention in a troubled young person's life can make a huge
                        change in their quality of life in the future, and prevent youth
                        ending up on the streets or turning to harmful methods of coping
                        with their situation.</p>

                    <p>Melbourne youth support employs trained youth outreach workers who
                        build rapport with young people facing vulnerability or
                        disadvantage, providing support, advice and a responsible adult to
                        confide in.</p>

                    <p>Our volunteer coordinators organise youth outreach events such as
                        food banks and information sessions as well as source, train and
                        coordinate our amazing volunteers - ordinary folk who want to make a
                        difference in a young person's life.</p>

                    <p>Our hard working community support officers handle the office work
                        involved in youth outreach, tracking individual cases, assisting our
                        outreach officers and volunteer coordinators to provide the
                        personalised support needed for each case.</p>

                    <p>Together, our team works to create strong connections between young
                        people and their communities. Providing support early in someone's
                        life is critical.</p>

                    <p>A critical aspect of our program is to hold regular mentoring, sports
                        and recreation sessions, to provide youth free, regular events where
                        they may enjoy themselves and connect with others.</p>

                </div>
                <!-- Image source: https://creativeyouthland.org/projects/ -->
                <img src="styles/images/indexGraphic.jpg" alt="Youth Power Graphic">
            </div>

        </div>
        <!--    Container for program schedule table    -->
        <div id="programSchedule-layout" class="content-background-and-border">
            <h2>Program Schedule</h2>
            <table id="programSchedule">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Session 1 - Morning</th>
                        <th>Session 2 - Midday</th>
                        <th>Session 3 - Afternoon</th>
                        <th>Session 4 - Evening</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Mon</b></td>
                        <td><b>—</b></td>
                        <td>Mentoring</td>
                        <td colspan="2">Basketball</td>
                    </tr>
                    <tr>
                        <td><b>Tue</b></td>
                        <td>Study Support</td>
                        <td>Job Skills</td>
                        <td colspan="2">Computer Lab</td>
                    </tr>
                    <tr>
                        <td><b>Wed</b></td>
                        <td>Mentoring</td>
                        <td colspan="3">Fitness / Gym</td>
                    </tr>
                    <tr>
                        <td><b>Thu</b></td>
                        <td colspan="2">Music Making / Recording</td>
                        <td colspan="2">Visual Arts</td>
                    </tr>
                    <tr>
                        <td><b>Fri</b></td>
                        <td>Mentoring</td>
                        <td>Job Skills</td>
                        <td colspan="2">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--    Container for acknowledgment of country and Australian Indigenous flag  -->
        <div id="acknowledgment-of-country" class="content-background-and-border">
            <!-- Image source https://wall.alphacoders.com/big.php?i=785129 -->
            <img src="styles/images/Aboriginal-flag.jpg" alt="Image of Indigenous Australian Flag">
            <!-- This div ensures that the text remains in column layout, while maintaining indiviudal styling for the bold sentence at the end 
                    Example of inline styling to add padding to the text.
            -->
            <div style="padding-right:10px;">
                <p>Melbourne Youth Support Network would like to acknowledge the Wurundjeri People of the Kulin Nation,
                    and pay our respects to their Elders, past present and emerging. The Wurundjeri peoples were the
                    first practicing
                    scientists in this area, many thousands of years before colonisation. 
                    <br>
                    We thank them for their dedicated and ongoing care for the land that we are lucky to call home.
                    <br>
                    MYSN recognises that Aboriginal and Torres-Strait Islander peoples still experience significant
                    disadvantages across many areas, such as child protection, justice, health, housing and education.
                    <br>
                    We aim to include First Nations Peoples in our decision making processes regarding youth support, as
                    their knowledge is invaluable
                    in providing adequate support for all youths.
                </p>
                <p><b >MYSN does not tolerate racism or any other form of discrimination.</b>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer - consistent with the other pages -->
    <?php include ("footer.inc"); ?>
</body>

</html>