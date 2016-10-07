<form action="api/rules/1" method="POST">

    <input name="_method" value="PUT"/>

    <input name="interval" value="HOURLY"/>
    <input name="name" value="1"/>
    <input name="report_repeated" value="1"/>
    <input name="report_email" value="john.smith@jsmith.com"/>
    <input name="strategy" value="ECONOMIC"/>
    <input name="layer" value="ADS"/>

    <input name="actions[0]" value="1"/>
    <input name="actions[1]" value="2"/>

    <input name="conditions[0][id]" value="1"/>
    <input name="conditions[0][comparison]" value=">"/>
    <input name="conditions[0][comparable]" value="1"/>

    <input type="submit" value="Submit"/>
</form>