{'Hi,'|_}

{'You are receiving this message because you requested a password change. To change your password, please click this link within %d minutes:'|_|sprintf:$minutes}

{$homePage}auth/newPass?token={$token}

{'If you did not request this change, please discard this message.'|_}

{'Thank you,'|_}
{$signature}
