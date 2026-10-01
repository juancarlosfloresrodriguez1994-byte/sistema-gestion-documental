<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f4f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden;">

                    {{-- Header con gradiente azul --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #007ee8, #0060b8); padding: 36px 40px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0 0 6px 0;">
                                Sistema UGELAA
                            </h1>
                            <p style="color: rgba(255,255,255,0.8); font-size: 13px; margin: 0;">
                                Trámite Documentario — UGEL Alto Amazonas
                            </p>
                        </td>
                    </tr>

                    {{-- Cuerpo --}}
                    <tr>
                        <td style="padding: 36px 40px;">

                            <h2 style="color: #1a1a2e; font-size: 20px; font-weight: 700; margin: 0 0 16px 0;">
                                Verificación de Registro
                            </h2>

                            <p style="color: #4a5568; font-size: 14px; line-height: 1.7; margin: 0 0 12px 0;">
                                Hola <strong>{{ $nombreCompleto }}</strong>,
                            </p>

                            <p style="color: #4a5568; font-size: 14px; line-height: 1.7; margin: 0 0 24px 0;">
                                Hemos recibido una solicitud de registro en el <strong>Sistema de Trámite Documentario de la UGEL Alto Amazonas</strong>.
                                Para completar tu registro, haz clic en el siguiente botón:
                            </p>

                            {{-- Botón de verificación --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 24px auto;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #007ee8, #0060b8); border-radius: 10px;">
                                        <a href="{{ $enlaceVerificacion }}"
                                           style="display: inline-block; padding: 14px 40px; color: #ffffff; font-size: 14px; font-weight: 600; text-decoration: none; letter-spacing: 0.5px; text-transform: uppercase;">
                                            ✅ Verificar mi Registro
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Advertencia de expiración --}}
                            <div style="background-color: #fff8e6; border-left: 4px solid #f5a524; border-radius: 8px; padding: 14px 18px; margin-bottom: 24px;">
                                <p style="color: #92600a; font-size: 13px; margin: 0; font-weight: 600;">
                                    ⏰ Este enlace expira en {{ $minutosExpiracion }} minutos.
                                </p>
                                <p style="color: #92600a; font-size: 12px; margin: 6px 0 0 0;">
                                    Si no completas la verificación antes de ese tiempo, deberás registrarte nuevamente.
                                </p>
                            </div>

                            {{-- Link alternativo --}}
                            <p style="color: #718096; font-size: 12px; line-height: 1.6; margin: 0 0 8px 0;">
                                Si el botón no funciona, copia y pega este enlace en tu navegador:
                            </p>
                            <p style="color: #007ee8; font-size: 12px; word-break: break-all; margin: 0 0 24px 0;">
                                {{ $enlaceVerificacion }}
                            </p>

                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                            <p style="color: #a0aec0; font-size: 11px; line-height: 1.6; margin: 0;">
                                Si no solicitaste este registro, puedes ignorar este correo de forma segura. Ninguna cuenta será creada.
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f7fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #a0aec0; font-size: 11px; margin: 0;">
                                &copy; {{ date('Y') }} UGEL Alto Amazonas — Sistema de Trámite Documentario
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
