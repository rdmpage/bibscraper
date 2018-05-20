<?xml version="1.0"?>
<xsl:stylesheet xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:tp="http://www.plazi.org/taxpub" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" exclude-result-prefixes="mml tp xlink" version="1.0">
	<xsl:output encoding="utf-8" indent="yes" method="text" version="1.0"/>


<xsl:template name="escapeQuote">
      <xsl:param name="pText" select="."/>

      <xsl:if test="string-length($pText) >0">
       <xsl:value-of select=
        "substring-before(concat($pText, '&quot;'), '&quot;')"/>

       <xsl:if test="contains($pText, '&quot;')">
        <xsl:text>\"</xsl:text>

        <xsl:call-template name="escapeQuote">
          <xsl:with-param name="pText" select=
          "substring-after($pText, '&quot;')"/>
        </xsl:call-template>
       </xsl:if>
      </xsl:if>
    </xsl:template>


	<xsl:template match="/">
		<xsl:apply-templates select="//back"/>
	</xsl:template>
	<xsl:template match="//back">
		<xsl:apply-templates select="ref-list"/>
	</xsl:template>
	<!-- references -->
	<xsl:template match="ref-list">
		<xsl:text>{</xsl:text>
			<xsl:apply-templates select="ref"/>
		<xsl:text>}</xsl:text>
	</xsl:template>

	<!-- Reference list -->
	<xsl:template match="ref">
		<xsl:if test="position() != 1">
			<xsl:text>,&#x0D;</xsl:text>
		</xsl:if>

		<xsl:text>"</xsl:text><xsl:value-of select="@id"/><xsl:text>":</xsl:text>
		<xsl:text>{&#x0D;</xsl:text>
		<xsl:text>"id":"</xsl:text><xsl:value-of select="@id"/><xsl:text>"</xsl:text>
			<xsl:apply-templates select="mixed-citation"/>
		<xsl:text>&#x0D;}</xsl:text>
	</xsl:template>

	<!-- authors -->
	<xsl:template match="//person-group">
		<xsl:apply-templates select="name"/>
	</xsl:template>
	<xsl:template match="name">
		<xsl:if test="position() != 1">
			<xsl:text>,</xsl:text>
		</xsl:if>
		
		<xsl:text>&#x0D;{</xsl:text>
			<xsl:text>"firstname":"</xsl:text><xsl:value-of select="given-names"/><xsl:text>",</xsl:text>
			<xsl:text>"lastname":"</xsl:text><xsl:value-of select="surname"/><xsl:text>",</xsl:text>
			<xsl:text>"name":"</xsl:text><xsl:value-of select="given-names"/><xsl:text> </xsl:text><xsl:value-of select="surname"/><xsl:text>"</xsl:text>
		<xsl:text>}</xsl:text>
	</xsl:template>

	<!-- a citation -->
	<xsl:template match="mixed-citation">

		<xsl:text>,&#x0D;"author":[</xsl:text>
			<xsl:apply-templates select="person-group[1]"/>
		<xsl:text>&#x0D;]</xsl:text>

		

		<xsl:choose>
			<!-- book chapter -->
			<xsl:when test="article-title and source and issue-title">
				<!-- type -->
				<xsl:text>,&#x0D;"type":"chapter"</xsl:text>

				<xsl:text>,&#x0D;"title":"</xsl:text>
					<xsl:value-of select="article-title"/>
				<xsl:text>"</xsl:text>

				<!-- book containing chaper -->
				<xsl:text>,&#x0D;"book":{</xsl:text>
					<xsl:text>&#x0D;"title":"</xsl:text>
						<xsl:value-of select="issue-title"/>
					<xsl:text>"</xsl:text>

				<xsl:text>&#x0D;}</xsl:text>

				<xsl:if test="fpage">
					<xsl:text>,&#x0D;"pages":"</xsl:text><xsl:value-of select="fpage"/>
					<xsl:if test="lpage">
						<xsl:text>--</xsl:text><xsl:value-of select="lpage"/>
					</xsl:if>
					<xsl:text>"</xsl:text>
				</xsl:if>


				
			</xsl:when>

			<!-- article -->
			<xsl:when test="article-title and source and volume">
				<!-- type -->
				<xsl:text>,&#x0D;"type":"article"</xsl:text>

				<xsl:text>,&#x0D;"title":"</xsl:text>
					<xsl:call-template name="escapeQuote">
						<xsl:with-param name="pText" select="article-title"/>
					</xsl:call-template>
				<xsl:text>"</xsl:text>

				<xsl:text>,&#x0D;"journal":{</xsl:text>

				<xsl:text>&#x0D;"name":"</xsl:text><xsl:value-of select="source"/><xsl:text>"</xsl:text>
			
				<xsl:choose>
					<xsl:when test="contains(volume, ')')">
						<xsl:variable name="after" select="substring-after(volume, '(')"/>
						<xsl:variable name="issue" select="substring-before($after, ')')"/>
						<xsl:variable name="before" select="substring-before(volume, '(')"/>
						<xsl:text>,&#x0D;"volume":"</xsl:text>
						<xsl:value-of select="$before"/>
						<xsl:text>"</xsl:text>

						<xsl:text>,&#x0D;"issue":"</xsl:text>						<xsl:value-of select="$issue"/>
						<xsl:text>"</xsl:text>

					</xsl:when>
					<xsl:otherwise>
						<xsl:text>,&#x0D;"volume":"</xsl:text>
						<xsl:value-of select="volume"/>
						<xsl:text>"</xsl:text>
					</xsl:otherwise>
				</xsl:choose>

				<xsl:if test="fpage">
					<xsl:text>,&#x0D;"pages":"</xsl:text><xsl:value-of select="fpage"/>
					<xsl:if test="lpage">
						<xsl:text>--</xsl:text><xsl:value-of select="lpage"/>
					</xsl:if>
					<xsl:text>"</xsl:text>
				</xsl:if>
					
	
				<xsl:text>&#x0D;}</xsl:text>
			</xsl:when>

			<!-- book -->
			<xsl:when test="source and publisher-name">
				<xsl:text>,&#x0D;"type":"book"</xsl:text>

				<xsl:text>,&#x0D;"title":"</xsl:text>
					<xsl:value-of select="source"/>
				<xsl:text>"</xsl:text>

				<!--
				<xsl:text> </xsl:text>
				<xsl:value-of select="publisher-name"/>
				<xsl:text> </xsl:text>
				<xsl:value-of select="publisher-loc"/>
-->

			</xsl:when>

			<!-- other -->
			<xsl:otherwise>
				<xsl:text>,&#x0D;"type":"generic"</xsl:text>

				<xsl:text>,&#x0D;"title":"</xsl:text>
					<xsl:value-of select="source"/>
				<xsl:text>"</xsl:text>
			</xsl:otherwise>

	
		</xsl:choose>

		<xsl:text>,&#x0D;"year":"</xsl:text>
			<xsl:value-of select="year"/>
		<xsl:text>"</xsl:text>


				<!-- any existing identifiers -->
				<xsl:text>,&#x0D;"identifier":[</xsl:text>
				<xsl:for-each select="ext-link">
					<xsl:choose>
						<xsl:when test="contains(@xlink:href, 'http://dx.doi.org/')">
							<xsl:text>{"type":"doi",</xsl:text>
							<xsl:text>"id":"</xsl:text><xsl:value-of select="substring-after(@xlink:href, 'http://dx.doi.org/')"/><xsl:text>"</xsl:text>
							<xsl:text>}</xsl:text>
						</xsl:when>
						<xsl:otherwise>
								</xsl:otherwise>
					</xsl:choose>
				</xsl:for-each>
				<xsl:text>]&#x0D;</xsl:text>



	</xsl:template>


</xsl:stylesheet>
