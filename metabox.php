<div class="tkt_sei_box">
    <style scoped>
        .tkt_sei_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .tkt_sei_field{
            display: contents;
        }
    </style>
    <p class="meta-options tkt_sei_field">
        <label for="tkt_sei_email_address">Email</label>
        <input id="tkt_sei_email_address" type="email" name="tkt_sei_email_address"
        value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'tkt_sei_email_address', true ) );?>">
    </p>
</div>
